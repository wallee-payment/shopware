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

namespace Wallee\Sdk\Model;

use Wallee\Sdk\ValidationException;

/**
 * TransactionPending model
 *
 * @category    Class
 * @description 
 * @package     Wallee\Sdk
 * @author      customweb GmbH
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache License v2
 * @link        https://github.com/wallee-payment/wallee-php-sdk
 */
class TransactionPending extends Transaction  {

	/**
	 * The original name of the model.
	 *
	 * @var string
	 */
	private static $swaggerModelName = 'Transaction.Pending';

	/**
	 * An array of property to type mappings. Used for (de)serialization.
	 *
	 * @var string[]
	 */
	private static $swaggerTypes = array(
	);

	/**
	 * Returns an array of property to type mappings.
	 *
	 * @return string[]
	 */
	public static function swaggerTypes() {
		return self::$swaggerTypes + parent::swaggerTypes();
	}

	


	/**
	 * Constructor.
	 *
	 * @param mixed[] $data an associated array of property values initializing the model
	 */
	public function __construct(array $data = null) {
		parent::__construct($data);

		if (isset($data['allowedPaymentMethodBrands']) && $data['allowedPaymentMethodBrands'] != null) {
			$this->setAllowedPaymentMethodBrands($data['allowedPaymentMethodBrands']);
		}
		if (isset($data['allowedPaymentMethodConfigurations']) && $data['allowedPaymentMethodConfigurations'] != null) {
			$this->setAllowedPaymentMethodConfigurations($data['allowedPaymentMethodConfigurations']);
		}
		if (isset($data['billingAddress']) && $data['billingAddress'] != null) {
			$this->setBillingAddress($data['billingAddress']);
		}
		if (isset($data['currency']) && $data['currency'] != null) {
			$this->setCurrency($data['currency']);
		}
		if (isset($data['customerEmailAddress']) && $data['customerEmailAddress'] != null) {
			$this->setCustomerEmailAddress($data['customerEmailAddress']);
		}
		if (isset($data['customerId']) && $data['customerId'] != null) {
			$this->setCustomerId($data['customerId']);
		}
		if (isset($data['failedUrl']) && $data['failedUrl'] != null) {
			$this->setFailedUrl($data['failedUrl']);
		}
		if (isset($data['invoiceMerchantReference']) && $data['invoiceMerchantReference'] != null) {
			$this->setInvoiceMerchantReference($data['invoiceMerchantReference']);
		}
		if (isset($data['language']) && $data['language'] != null) {
			$this->setLanguage($data['language']);
		}
		if (isset($data['lineItems']) && $data['lineItems'] != null) {
			$this->setLineItems($data['lineItems']);
		}
		if (isset($data['merchantReference']) && $data['merchantReference'] != null) {
			$this->setMerchantReference($data['merchantReference']);
		}
		if (isset($data['metaData']) && $data['metaData'] != null) {
			$this->setMetaData($data['metaData']);
		}
		if (isset($data['shippingAddress']) && $data['shippingAddress'] != null) {
			$this->setShippingAddress($data['shippingAddress']);
		}
		if (isset($data['shippingMethod']) && $data['shippingMethod'] != null) {
			$this->setShippingMethod($data['shippingMethod']);
		}
		if (isset($data['successUrl']) && $data['successUrl'] != null) {
			$this->setSuccessUrl($data['successUrl']);
		}
		if (isset($data['token']) && $data['token'] != null) {
			$this->setToken($data['token']);
		}
		if (isset($data['id']) && $data['id'] != null) {
			$this->setId($data['id']);
		}
		if (isset($data['version']) && $data['version'] != null) {
			$this->setVersion($data['version']);
		}
	}


	/**
	 * Returns allowedPaymentMethodBrands.
	 *
	 * 
	 *
	 * @return \Wallee\Sdk\Model\PaymentMethodBrand[]
	 */
	public function getAllowedPaymentMethodBrands() {
		return parent::getAllowedPaymentMethodBrands();
	}

	/**
	 * Sets allowedPaymentMethodBrands.
	 *
	 * @param \Wallee\Sdk\Model\PaymentMethodBrand[] $allowedPaymentMethodBrands
	 * @return TransactionPending
	 */
	public function setAllowedPaymentMethodBrands($allowedPaymentMethodBrands) {
		return parent::setAllowedPaymentMethodBrands($allowedPaymentMethodBrands);
	}

	/**
	 * Returns allowedPaymentMethodConfigurations.
	 *
	 * 
	 *
	 * @return int[]
	 */
	public function getAllowedPaymentMethodConfigurations() {
		return parent::getAllowedPaymentMethodConfigurations();
	}

	/**
	 * Sets allowedPaymentMethodConfigurations.
	 *
	 * @param int[] $allowedPaymentMethodConfigurations
	 * @return TransactionPending
	 */
	public function setAllowedPaymentMethodConfigurations($allowedPaymentMethodConfigurations) {
		return parent::setAllowedPaymentMethodConfigurations($allowedPaymentMethodConfigurations);
	}

	/**
	 * Returns billingAddress.
	 *
	 * 
	 *
	 * @return \Wallee\Sdk\Model\AddressCreate
	 */
	public function getBillingAddress() {
		return parent::getBillingAddress();
	}

	/**
	 * Sets billingAddress.
	 *
	 * @param \Wallee\Sdk\Model\AddressCreate $billingAddress
	 * @return TransactionPending
	 */
	public function setBillingAddress($billingAddress) {
		return parent::setBillingAddress($billingAddress);
	}

	/**
	 * Returns currency.
	 *
	 * 
	 *
	 * @return string
	 */
	public function getCurrency() {
		return parent::getCurrency();
	}

	/**
	 * Sets currency.
	 *
	 * @param string $currency
	 * @return TransactionPending
	 */
	public function setCurrency($currency) {
		return parent::setCurrency($currency);
	}

	/**
	 * Returns customerEmailAddress.
	 *
	 * The customer email address is the email address of the customer. If no email address is used provided on the shipping or billing address this address is used.
	 *
	 * @return string
	 */
	public function getCustomerEmailAddress() {
		return parent::getCustomerEmailAddress();
	}

	/**
	 * Sets customerEmailAddress.
	 *
	 * @param string $customerEmailAddress
	 * @return TransactionPending
	 */
	public function setCustomerEmailAddress($customerEmailAddress) {
		return parent::setCustomerEmailAddress($customerEmailAddress);
	}

	/**
	 * Returns customerId.
	 *
	 * 
	 *
	 * @return string
	 */
	public function getCustomerId() {
		return parent::getCustomerId();
	}

	/**
	 * Sets customerId.
	 *
	 * @param string $customerId
	 * @return TransactionPending
	 */
	public function setCustomerId($customerId) {
		return parent::setCustomerId($customerId);
	}

	/**
	 * Returns failedUrl.
	 *
	 * The user will be redirected to failed URL when the transaction could not be authorized or completed. In case no failed URL is specified a default failed page will be displayed.
	 *
	 * @return string
	 */
	public function getFailedUrl() {
		return parent::getFailedUrl();
	}

	/**
	 * Sets failedUrl.
	 *
	 * @param string $failedUrl
	 * @return TransactionPending
	 */
	public function setFailedUrl($failedUrl) {
		return parent::setFailedUrl($failedUrl);
	}

	/**
	 * Returns invoiceMerchantReference.
	 *
	 * 
	 *
	 * @return string
	 */
	public function getInvoiceMerchantReference() {
		return parent::getInvoiceMerchantReference();
	}

	/**
	 * Sets invoiceMerchantReference.
	 *
	 * @param string $invoiceMerchantReference
	 * @return TransactionPending
	 */
	public function setInvoiceMerchantReference($invoiceMerchantReference) {
		return parent::setInvoiceMerchantReference($invoiceMerchantReference);
	}

	/**
	 * Returns language.
	 *
	 * 
	 *
	 * @return string
	 */
	public function getLanguage() {
		return parent::getLanguage();
	}

	/**
	 * Sets language.
	 *
	 * @param string $language
	 * @return TransactionPending
	 */
	public function setLanguage($language) {
		return parent::setLanguage($language);
	}

	/**
	 * Returns lineItems.
	 *
	 * 
	 *
	 * @return \Wallee\Sdk\Model\LineItemCreate[]
	 */
	public function getLineItems() {
		return parent::getLineItems();
	}

	/**
	 * Sets lineItems.
	 *
	 * @param \Wallee\Sdk\Model\LineItemCreate[] $lineItems
	 * @return TransactionPending
	 */
	public function setLineItems($lineItems) {
		return parent::setLineItems($lineItems);
	}

	/**
	 * Returns merchantReference.
	 *
	 * 
	 *
	 * @return string
	 */
	public function getMerchantReference() {
		return parent::getMerchantReference();
	}

	/**
	 * Sets merchantReference.
	 *
	 * @param string $merchantReference
	 * @return TransactionPending
	 */
	public function setMerchantReference($merchantReference) {
		return parent::setMerchantReference($merchantReference);
	}

	/**
	 * Returns metaData.
	 *
	 * Meta data allow to store additional data along the object.
	 *
	 * @return map[string,string]
	 */
	public function getMetaData() {
		return parent::getMetaData();
	}

	/**
	 * Sets metaData.
	 *
	 * @param map[string,string] $metaData
	 * @return TransactionPending
	 */
	public function setMetaData($metaData) {
		return parent::setMetaData($metaData);
	}

	/**
	 * Returns shippingAddress.
	 *
	 * 
	 *
	 * @return \Wallee\Sdk\Model\AddressCreate
	 */
	public function getShippingAddress() {
		return parent::getShippingAddress();
	}

	/**
	 * Sets shippingAddress.
	 *
	 * @param \Wallee\Sdk\Model\AddressCreate $shippingAddress
	 * @return TransactionPending
	 */
	public function setShippingAddress($shippingAddress) {
		return parent::setShippingAddress($shippingAddress);
	}

	/**
	 * Returns shippingMethod.
	 *
	 * 
	 *
	 * @return string
	 */
	public function getShippingMethod() {
		return parent::getShippingMethod();
	}

	/**
	 * Sets shippingMethod.
	 *
	 * @param string $shippingMethod
	 * @return TransactionPending
	 */
	public function setShippingMethod($shippingMethod) {
		return parent::setShippingMethod($shippingMethod);
	}

	/**
	 * Returns successUrl.
	 *
	 * The user will be redirected to success URL when the transaction could be authorized or completed. In case no success URL is specified a default success page will be displayed.
	 *
	 * @return string
	 */
	public function getSuccessUrl() {
		return parent::getSuccessUrl();
	}

	/**
	 * Sets successUrl.
	 *
	 * @param string $successUrl
	 * @return TransactionPending
	 */
	public function setSuccessUrl($successUrl) {
		return parent::setSuccessUrl($successUrl);
	}

	/**
	 * Returns token.
	 *
	 * 
	 *
	 * @return int
	 */
	public function getToken() {
		return parent::getToken();
	}

	/**
	 * Sets token.
	 *
	 * @param int $token
	 * @return TransactionPending
	 */
	public function setToken($token) {
		return parent::setToken($token);
	}

	/**
	 * Returns id.
	 *
	 * The ID is the primary key of the entity. The ID identifies the entity uniquely.
	 *
	 * @return int
	 */
	public function getId() {
		return parent::getId();
	}

	/**
	 * Sets id.
	 *
	 * @param int $id
	 * @return TransactionPending
	 */
	public function setId($id) {
		return parent::setId($id);
	}

	/**
	 * Returns version.
	 *
	 * The version number indicates the version of the entity. The version is incremented whenever the entity is changed.
	 *
	 * @return int
	 */
	public function getVersion() {
		return parent::getVersion();
	}

	/**
	 * Sets version.
	 *
	 * @param int $version
	 * @return TransactionPending
	 */
	public function setVersion($version) {
		return parent::setVersion($version);
	}

	/**
	 * Validates the model's properties and throws a ValidationException if the validation fails.
	 *
	 * @throws ValidationException
	 */
	public function validate() {
		parent::validate();

		if ($this->getId() === null) {
			throw new ValidationException("'id' can't be null", 'id', $this);
		}
		if ($this->getVersion() === null) {
			throw new ValidationException("'version' can't be null", 'version', $this);
		}
	}

	/**
	 * Returns true if all the properties in the model are valid.
	 *
	 * @return boolean
	 */
	public function isValid() {
		try {
			$this->validate();
			return true;
		} catch (ValidationException $e) {
			return false;
		}
	}

	/**
	 * Returns the string presentation of the object.
	 *
	 * @return string
	 */
	public function __toString() {
		if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
			return json_encode(\Wallee\Sdk\ObjectSerializer::sanitizeForSerialization($this), JSON_PRETTY_PRINT);
		}

		return json_encode(\Wallee\Sdk\ObjectSerializer::sanitizeForSerialization($this));
	}

}

