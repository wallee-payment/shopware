<?php
/**
 * Wallee SDK
 *
 * This library allows to interact with the Wallee payment service.
 * Wallee SDK: 1.0.0
 * 
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Wallee\Sdk\Http;

use Wallee\Sdk\ApiClient;

/**
 * This class sends API calls via cURL.
 *
 * @category Class
 * @package  Wallee\Sdk\Http
 * @author   customweb GmbH
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache License v2
 * @link	 https://github.com/wallee-payment/wallee-php-sdk
 */
final class CurlHttpClient implements IHttpClient {

	public function isSupported() {
		return function_exists('curl_version');
	}

	public function send(ApiClient $apiClient, HttpRequest $request) {
		$curl = curl_init();
		// set timeout, if needed
		if ($apiClient->getConnectionTimeout() !== 0) {
			curl_setopt($curl, CURLOPT_TIMEOUT, $apiClient->getConnectionTimeout());
		}
		// return the result on success, rather than just true
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($curl, CURLOPT_HTTPHEADER, $request->getHeaders());

		// disable SSL verification, if needed
		if ($apiClient->isCertificateAuthorityCheckEnabled() === false) {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		} else {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($curl, CURLOPT_CAINFO, $apiClient->getCertificateAuthority());
		}

		if ($request->getMethod() === HttpRequest::POST) {
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request->getBody());
		} elseif ($request->getMethod() === HttpRequest::HEAD) {
			curl_setopt($curl, CURLOPT_NOBODY, true);
		} elseif ($request->getMethod() === HttpRequest::OPTIONS) {
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'OPTIONS');
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request->getBody());
		} elseif ($request->getMethod() === HttpRequest::PATCH) {
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request->getBody());
		} elseif ($request->getMethod() === HttpRequest::PUT) {
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request->getBody());
		} elseif ($request->getMethod() === HttpRequest::DELETE) {
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request->getBody());
		} elseif ($request->getMethod() !== HttpRequest::GET) {
			throw new ConnectionException($request->getUrl(), 'Method ' . $request->getMethod() . ' is not recognized.');
		}
		curl_setopt($curl, CURLOPT_URL, $request->getUrl());

		// Set user agent
		curl_setopt($curl, CURLOPT_USERAGENT, $request->getUserAgent());

		// debugging for curl
		$debugFilePointer = fopen($apiClient->getDebugFile(), 'a');
		if ($apiClient->isDebuggingEnabled()) {
			error_log("[DEBUG] HTTP Request body  ~BEGIN~".PHP_EOL.print_r($request->getBody(), true).PHP_EOL."~END~".PHP_EOL, 3, $apiClient->getDebugFile());

			curl_setopt($curl, CURLOPT_VERBOSE, 1);
			curl_setopt($curl, CURLOPT_STDERR, $debugFilePointer);
		} else {
			curl_setopt($curl, CURLOPT_VERBOSE, 0);
		}

		// obtain the HTTP response headers
		curl_setopt($curl, CURLOPT_HEADER, 1);

		// Make the request
		$response = curl_exec($curl);
		$response = $this->handleResponse($apiClient, $curl, $response, $request->getUrl());
		curl_close($curl);
		fclose($debugFilePointer);

		return $response;
	}

	/**
	 * Puts together the HTTP response.
	 *
	 * @param ApiClient $apiClient the API client instance
	 * @param resource $curl the cURL handler
	 * @param mixed $response the response the of HTTP request
	 * @param string $url the url of the HTTP request
	 * @return HttpResponse
	 */
	private function handleResponse(ApiClient $apiClient, $curl, $response, $url) {
		$http_header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		$http_header = substr($response, 0, $http_header_size);
		$http_body = substr($response, $http_header_size);
		$response_info = curl_getinfo($curl);

		// debug HTTP response body
		if ($apiClient->isDebuggingEnabled()) {
			error_log("[DEBUG] HTTP Response body ~BEGIN~".PHP_EOL.print_r($http_body, true).PHP_EOL."~END~".PHP_EOL, 3, $apiClient->getDebugFile());
		}

		if ($response_info['http_code'] === 0) {
			$curl_error_message = curl_error($curl);

			// curl_exec can sometimes fail but still return a blank message from curl_error().
			if (!empty($curl_error_message)) {
				throw new ConnectionException($url, $curl_error_message);
			} else {
				throw new ConnectionException($url, 'API call failed for an unknown reason. This could happen if you are disconnected from the network.');
			}
		} else {
			return new HttpResponse($response_info['http_code'], $http_header, $http_body);
		}
	}

}