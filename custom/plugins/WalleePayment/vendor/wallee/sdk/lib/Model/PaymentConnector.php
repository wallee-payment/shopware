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
 * PaymentConnector model
 *
 * @category    Class
 * @description 
 * @package     Wallee\Sdk
 * @author      customweb GmbH
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache License v2
 * @link        https://github.com/wallee-payment/wallee-php-sdk
 */
class PaymentConnector  {

	/**
	 * The original name of the model.
	 *
	 * @var string
	 */
	private static $swaggerModelName = 'PaymentConnector';

	/**
	 * An array of property to type mappings. Used for (de)serialization.
	 *
	 * @var string[]
	 */
	private static $swaggerTypes = array(
		'dataCollectionType' => '\Wallee\Sdk\Model\DataCollectionType',
		'description' => 'map[string,string]',
		'feature' => '\Wallee\Sdk\Model\Feature',
		'id' => 'int',
		'name' => 'map[string,string]',
		'paymentMethod' => 'int',
		'paymentMethodBrand' => '\Wallee\Sdk\Model\PaymentMethodBrand',
		'primaryRiskTaker' => '\Wallee\Sdk\Model\PaymentPrimaryRiskTaker',
		'processor' => 'int',
		'supportedCustomersPresences' => '\Wallee\Sdk\Model\CustomersPresence[]',
		'supportedFeatures' => 'int[]'	);

	/**
	 * Returns an array of property to type mappings.
	 *
	 * @return string[]
	 */
	public static function swaggerTypes() {
		return self::$swaggerTypes;
	}

	

	/**
	 * 
	 *
	 * @var \Wallee\Sdk\Model\DataCollectionType
	 */
	private $dataCollectionType;

	/**
	 * 
	 *
	 * @var map[string,string]
	 */
	private $description;

	/**
	 * 
	 *
	 * @var \Wallee\Sdk\Model\Feature
	 */
	private $feature;

	/**
	 * The ID is the primary key of the entity. The ID identifies the entity uniquely.
	 *
	 * @var int
	 */
	private $id;

	/**
	 * 
	 *
	 * @var map[string,string]
	 */
	private $name;

	/**
	 * 
	 *
	 * @var int
	 */
	private $paymentMethod;

	/**
	 * 
	 *
	 * @var \Wallee\Sdk\Model\PaymentMethodBrand
	 */
	private $paymentMethodBrand;

	/**
	 * 
	 *
	 * @var \Wallee\Sdk\Model\PaymentPrimaryRiskTaker
	 */
	private $primaryRiskTaker;

	/**
	 * 
	 *
	 * @var int
	 */
	private $processor;

	/**
	 * 
	 *
	 * @var \Wallee\Sdk\Model\CustomersPresence[]
	 */
	private $supportedCustomersPresences;

	/**
	 * 
	 *
	 * @var int[]
	 */
	private $supportedFeatures;


	/**
	 * Constructor.
	 *
	 * @param mixed[] $data an associated array of property values initializing the model
	 */
	public function __construct(array $data = null) {
		if (isset($data['dataCollectionType']) && $data['dataCollectionType'] != null) {
			$this->setDataCollectionType($data['dataCollectionType']);
		}
		if (isset($data['description']) && $data['description'] != null) {
			$this->setDescription($data['description']);
		}
		if (isset($data['feature']) && $data['feature'] != null) {
			$this->setFeature($data['feature']);
		}
		if (isset($data['name']) && $data['name'] != null) {
			$this->setName($data['name']);
		}
		if (isset($data['paymentMethodBrand']) && $data['paymentMethodBrand'] != null) {
			$this->setPaymentMethodBrand($data['paymentMethodBrand']);
		}
		if (isset($data['primaryRiskTaker']) && $data['primaryRiskTaker'] != null) {
			$this->setPrimaryRiskTaker($data['primaryRiskTaker']);
		}
		if (isset($data['supportedCustomersPresences']) && $data['supportedCustomersPresences'] != null) {
			$this->setSupportedCustomersPresences($data['supportedCustomersPresences']);
		}
		if (isset($data['supportedFeatures']) && $data['supportedFeatures'] != null) {
			$this->setSupportedFeatures($data['supportedFeatures']);
		}
	}


	/**
	 * Returns dataCollectionType.
	 *
	 * 
	 *
	 * @return \Wallee\Sdk\Model\DataCollectionType
	 */
	public function getDataCollectionType() {
		return $this->dataCollectionType;
	}

	/**
	 * Sets dataCollectionType.
	 *
	 * @param \Wallee\Sdk\Model\DataCollectionType $dataCollectionType
	 * @return PaymentConnector
	 */
	public function setDataCollectionType($dataCollectionType) {
		$this->dataCollectionType = $dataCollectionType;

		return $this;
	}

	/**
	 * Returns description.
	 *
	 * 
	 *
	 * @return map[string,string]
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Sets description.
	 *
	 * @param map[string,string] $description
	 * @return PaymentConnector
	 */
	public function setDescription($description) {
		$this->description = $description;

		return $this;
	}

	/**
	 * Returns feature.
	 *
	 * 
	 *
	 * @return \Wallee\Sdk\Model\Feature
	 */
	public function getFeature() {
		return $this->feature;
	}

	/**
	 * Sets feature.
	 *
	 * @param \Wallee\Sdk\Model\Feature $feature
	 * @return PaymentConnector
	 */
	public function setFeature($feature) {
		$this->feature = $feature;

		return $this;
	}

	/**
	 * Returns id.
	 *
	 * The ID is the primary key of the entity. The ID identifies the entity uniquely.
	 *
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Sets id.
	 *
	 * @param int $id
	 * @return PaymentConnector
	 */
	protected function setId($id) {
		$this->id = $id;

		return $this;
	}

	/**
	 * Returns name.
	 *
	 * 
	 *
	 * @return map[string,string]
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets name.
	 *
	 * @param map[string,string] $name
	 * @return PaymentConnector
	 */
	public function setName($name) {
		$this->name = $name;

		return $this;
	}

	/**
	 * Returns paymentMethod.
	 *
	 * 
	 *
	 * @return int
	 */
	public function getPaymentMethod() {
		return $this->paymentMethod;
	}

	/**
	 * Sets paymentMethod.
	 *
	 * @param int $paymentMethod
	 * @return PaymentConnector
	 */
	protected function setPaymentMethod($paymentMethod) {
		$this->paymentMethod = $paymentMethod;

		return $this;
	}

	/**
	 * Returns paymentMethodBrand.
	 *
	 * 
	 *
	 * @return \Wallee\Sdk\Model\PaymentMethodBrand
	 */
	public function getPaymentMethodBrand() {
		return $this->paymentMethodBrand;
	}

	/**
	 * Sets paymentMethodBrand.
	 *
	 * @param \Wallee\Sdk\Model\PaymentMethodBrand $paymentMethodBrand
	 * @return PaymentConnector
	 */
	public function setPaymentMethodBrand($paymentMethodBrand) {
		$this->paymentMethodBrand = $paymentMethodBrand;

		return $this;
	}

	/**
	 * Returns primaryRiskTaker.
	 *
	 * 
	 *
	 * @return \Wallee\Sdk\Model\PaymentPrimaryRiskTaker
	 */
	public function getPrimaryRiskTaker() {
		return $this->primaryRiskTaker;
	}

	/**
	 * Sets primaryRiskTaker.
	 *
	 * @param \Wallee\Sdk\Model\PaymentPrimaryRiskTaker $primaryRiskTaker
	 * @return PaymentConnector
	 */
	public function setPrimaryRiskTaker($primaryRiskTaker) {
		$this->primaryRiskTaker = $primaryRiskTaker;

		return $this;
	}

	/**
	 * Returns processor.
	 *
	 * 
	 *
	 * @return int
	 */
	public function getProcessor() {
		return $this->processor;
	}

	/**
	 * Sets processor.
	 *
	 * @param int $processor
	 * @return PaymentConnector
	 */
	protected function setProcessor($processor) {
		$this->processor = $processor;

		return $this;
	}

	/**
	 * Returns supportedCustomersPresences.
	 *
	 * 
	 *
	 * @return \Wallee\Sdk\Model\CustomersPresence[]
	 */
	public function getSupportedCustomersPresences() {
		return $this->supportedCustomersPresences;
	}

	/**
	 * Sets supportedCustomersPresences.
	 *
	 * @param \Wallee\Sdk\Model\CustomersPresence[] $supportedCustomersPresences
	 * @return PaymentConnector
	 */
	public function setSupportedCustomersPresences($supportedCustomersPresences) {
		$this->supportedCustomersPresences = $supportedCustomersPresences;

		return $this;
	}

	/**
	 * Returns supportedFeatures.
	 *
	 * 
	 *
	 * @return int[]
	 */
	public function getSupportedFeatures() {
		return $this->supportedFeatures;
	}

	/**
	 * Sets supportedFeatures.
	 *
	 * @param int[] $supportedFeatures
	 * @return PaymentConnector
	 */
	public function setSupportedFeatures($supportedFeatures) {
		$this->supportedFeatures = $supportedFeatures;

		return $this;
	}

	/**
	 * Validates the model's properties and throws a ValidationException if the validation fails.
	 *
	 * @throws ValidationException
	 */
	public function validate() {

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
