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

use \Wallee\Sdk\ValidationException;

/**
 * TransactionVoid model
 *
 * @category    Class
 * @description 
 * @package     Wallee\Sdk
 * @author      customweb GmbH
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache License v2
 * @link        https://github.com/wallee-payment/wallee-php-sdk
 */
class TransactionVoid extends TransactionAwareEntity  {

	/**
	 * The original name of the model.
	 *
	 * @var string
	 */
	private static $swaggerModelName = 'TransactionVoid';

	/**
	 * An array of property to type mappings. Used for (de)serialization.
	 *
	 * @var string[]
	 */
	private static $swaggerTypes = array(
		'createdBy' => 'int',
		'createdOn' => '\DateTime',
		'failedOn' => '\DateTime',
		'failureReason' => '\Wallee\Sdk\Model\FailureReason',
		'labels' => '\Wallee\Sdk\Model\Label[]',
		'language' => 'string',
		'mode' => 'string',
		'nextUpdateOn' => '\DateTime',
		'plannedPurgeDate' => '\DateTime',
		'processorReference' => 'string',
		'spaceViewId' => 'int',
		'state' => 'string',
		'succeededOn' => '\DateTime',
		'timeoutOn' => '\DateTime',
		'transaction' => '\Wallee\Sdk\Model\Transaction',
		'version' => 'int'	);

	/**
	 * Returns an array of property to type mappings.
	 *
	 * @return string[]
	 */
	public static function swaggerTypes() {
		return self::$swaggerTypes + parent::swaggerTypes();
	}

	
	/**
	 * Values of mode.
	 */
	const MODE_ONLINE = 'ONLINE';
	const MODE_OFFLINE = 'OFFLINE';
	
	/**
	 * Returns allowable values of mode.
	 *
	 * @return string[]
	 */
	public function getModeAllowableValues() {
		return array(
			self::MODE_ONLINE,
			self::MODE_OFFLINE,
		);
	}
	
	/**
	 * Values of state.
	 */
	const STATE_CREATE = 'CREATE';
	const STATE_PENDING = 'PENDING';
	const STATE_FAILED = 'FAILED';
	const STATE_SUCCESSFUL = 'SUCCESSFUL';
	
	/**
	 * Returns allowable values of state.
	 *
	 * @return string[]
	 */
	public function getStateAllowableValues() {
		return array(
			self::STATE_CREATE,
			self::STATE_PENDING,
			self::STATE_FAILED,
			self::STATE_SUCCESSFUL,
		);
	}
	

	/**
	 * 
	 *
	 * @var int
	 */
	private $createdBy;

	/**
	 * The created on date indicates the date on which the entity was stored into the database.
	 *
	 * @var \DateTime
	 */
	private $createdOn;

	/**
	 * 
	 *
	 * @var \DateTime
	 */
	private $failedOn;

	/**
	 * @var \Wallee\Sdk\Model\FailureReason
	 */
	private $failureReason;

	/**
	 * 
	 *
	 * @var \Wallee\Sdk\Model\Label[]
	 */
	private $labels;

	/**
	 * 
	 *
	 * @var string
	 */
	private $language;

	/**
	 * 
	 *
	 * @var string
	 */
	private $mode;

	/**
	 * 
	 *
	 * @var \DateTime
	 */
	private $nextUpdateOn;

	/**
	 * The planned purge date indicates when the entity is permanently removed. When the date is null the entity is not planned to be removed.
	 *
	 * @var \DateTime
	 */
	private $plannedPurgeDate;

	/**
	 * 
	 *
	 * @var string
	 */
	private $processorReference;

	/**
	 * @var int
	 */
	private $spaceViewId;

	/**
	 * 
	 *
	 * @var string
	 */
	private $state;

	/**
	 * 
	 *
	 * @var \DateTime
	 */
	private $succeededOn;

	/**
	 * 
	 *
	 * @var \DateTime
	 */
	private $timeoutOn;

	/**
	 * @var \Wallee\Sdk\Model\Transaction
	 */
	private $transaction;

	/**
	 * The version number indicates the version of the entity. The version is incremented whenever the entity is changed.
	 *
	 * @var int
	 */
	private $version;


	/**
	 * Constructor.
	 *
	 * @param mixed[] $data an associated array of property values initializing the model
	 */
	public function __construct(array $data = null) {
		parent::__construct($data);

		if (isset($data['failureReason']) && $data['failureReason'] != null) {
			$this->setFailureReason($data['failureReason']);
		}
		if (isset($data['labels']) && $data['labels'] != null) {
			$this->setLabels($data['labels']);
		}
		if (isset($data['spaceViewId']) && $data['spaceViewId'] != null) {
			$this->setSpaceViewId($data['spaceViewId']);
		}
		if (isset($data['transaction']) && $data['transaction'] != null) {
			$this->setTransaction($data['transaction']);
		}
	}


	/**
	 * Returns createdBy.
	 *
	 * 
	 *
	 * @return int
	 */
	public function getCreatedBy() {
		return $this->createdBy;
	}

	/**
	 * Sets createdBy.
	 *
	 * @param int $createdBy
	 * @return TransactionVoid
	 */
	protected function setCreatedBy($createdBy) {
		$this->createdBy = $createdBy;

		return $this;
	}

	/**
	 * Returns createdOn.
	 *
	 * The created on date indicates the date on which the entity was stored into the database.
	 *
	 * @return \DateTime
	 */
	public function getCreatedOn() {
		return $this->createdOn;
	}

	/**
	 * Sets createdOn.
	 *
	 * @param \DateTime $createdOn
	 * @return TransactionVoid
	 */
	protected function setCreatedOn($createdOn) {
		$this->createdOn = $createdOn;

		return $this;
	}

	/**
	 * Returns failedOn.
	 *
	 * 
	 *
	 * @return \DateTime
	 */
	public function getFailedOn() {
		return $this->failedOn;
	}

	/**
	 * Sets failedOn.
	 *
	 * @param \DateTime $failedOn
	 * @return TransactionVoid
	 */
	protected function setFailedOn($failedOn) {
		$this->failedOn = $failedOn;

		return $this;
	}

	/**
	 * Returns failureReason.
	 *
	 * @return \Wallee\Sdk\Model\FailureReason
	 */
	public function getFailureReason() {
		return $this->failureReason;
	}

	/**
	 * Sets failureReason.
	 *
	 * @param \Wallee\Sdk\Model\FailureReason $failureReason
	 * @return TransactionVoid
	 */
	public function setFailureReason($failureReason) {
		$this->failureReason = $failureReason;

		return $this;
	}

	/**
	 * Returns labels.
	 *
	 * 
	 *
	 * @return \Wallee\Sdk\Model\Label[]
	 */
	public function getLabels() {
		return $this->labels;
	}

	/**
	 * Sets labels.
	 *
	 * @param \Wallee\Sdk\Model\Label[] $labels
	 * @return TransactionVoid
	 */
	public function setLabels($labels) {
		$this->labels = $labels;

		return $this;
	}

	/**
	 * Returns language.
	 *
	 * 
	 *
	 * @return string
	 */
	public function getLanguage() {
		return $this->language;
	}

	/**
	 * Sets language.
	 *
	 * @param string $language
	 * @return TransactionVoid
	 */
	protected function setLanguage($language) {
		$this->language = $language;

		return $this;
	}

	/**
	 * Returns mode.
	 *
	 * 
	 *
	 * @return string
	 */
	public function getMode() {
		return $this->mode;
	}

	/**
	 * Sets mode.
	 *
	 * @param string $mode
	 * @return TransactionVoid
	 */
	protected function setMode($mode) {
		$allowed_values = array('ONLINE', 'OFFLINE');
		if (!is_null($mode) && (!in_array($mode, $allowed_values))) {
			throw new \InvalidArgumentException("Invalid value for 'mode', must be one of 'ONLINE', 'OFFLINE'");
		}
		$this->mode = $mode;

		return $this;
	}

	/**
	 * Returns nextUpdateOn.
	 *
	 * 
	 *
	 * @return \DateTime
	 */
	public function getNextUpdateOn() {
		return $this->nextUpdateOn;
	}

	/**
	 * Sets nextUpdateOn.
	 *
	 * @param \DateTime $nextUpdateOn
	 * @return TransactionVoid
	 */
	protected function setNextUpdateOn($nextUpdateOn) {
		$this->nextUpdateOn = $nextUpdateOn;

		return $this;
	}

	/**
	 * Returns plannedPurgeDate.
	 *
	 * The planned purge date indicates when the entity is permanently removed. When the date is null the entity is not planned to be removed.
	 *
	 * @return \DateTime
	 */
	public function getPlannedPurgeDate() {
		return $this->plannedPurgeDate;
	}

	/**
	 * Sets plannedPurgeDate.
	 *
	 * @param \DateTime $plannedPurgeDate
	 * @return TransactionVoid
	 */
	protected function setPlannedPurgeDate($plannedPurgeDate) {
		$this->plannedPurgeDate = $plannedPurgeDate;

		return $this;
	}

	/**
	 * Returns processorReference.
	 *
	 * 
	 *
	 * @return string
	 */
	public function getProcessorReference() {
		return $this->processorReference;
	}

	/**
	 * Sets processorReference.
	 *
	 * @param string $processorReference
	 * @return TransactionVoid
	 */
	protected function setProcessorReference($processorReference) {
		$this->processorReference = $processorReference;

		return $this;
	}

	/**
	 * Returns spaceViewId.
	 *
	 * @return int
	 */
	public function getSpaceViewId() {
		return $this->spaceViewId;
	}

	/**
	 * Sets spaceViewId.
	 *
	 * @param int $spaceViewId
	 * @return TransactionVoid
	 */
	public function setSpaceViewId($spaceViewId) {
		$this->spaceViewId = $spaceViewId;

		return $this;
	}

	/**
	 * Returns state.
	 *
	 * 
	 *
	 * @return string
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * Sets state.
	 *
	 * @param string $state
	 * @return TransactionVoid
	 */
	protected function setState($state) {
		$allowed_values = array('CREATE', 'PENDING', 'FAILED', 'SUCCESSFUL');
		if (!is_null($state) && (!in_array($state, $allowed_values))) {
			throw new \InvalidArgumentException("Invalid value for 'state', must be one of 'CREATE', 'PENDING', 'FAILED', 'SUCCESSFUL'");
		}
		$this->state = $state;

		return $this;
	}

	/**
	 * Returns succeededOn.
	 *
	 * 
	 *
	 * @return \DateTime
	 */
	public function getSucceededOn() {
		return $this->succeededOn;
	}

	/**
	 * Sets succeededOn.
	 *
	 * @param \DateTime $succeededOn
	 * @return TransactionVoid
	 */
	protected function setSucceededOn($succeededOn) {
		$this->succeededOn = $succeededOn;

		return $this;
	}

	/**
	 * Returns timeoutOn.
	 *
	 * 
	 *
	 * @return \DateTime
	 */
	public function getTimeoutOn() {
		return $this->timeoutOn;
	}

	/**
	 * Sets timeoutOn.
	 *
	 * @param \DateTime $timeoutOn
	 * @return TransactionVoid
	 */
	protected function setTimeoutOn($timeoutOn) {
		$this->timeoutOn = $timeoutOn;

		return $this;
	}

	/**
	 * Returns transaction.
	 *
	 * @return \Wallee\Sdk\Model\Transaction
	 */
	public function getTransaction() {
		return $this->transaction;
	}

	/**
	 * Sets transaction.
	 *
	 * @param \Wallee\Sdk\Model\Transaction $transaction
	 * @return TransactionVoid
	 */
	public function setTransaction($transaction) {
		$this->transaction = $transaction;

		return $this;
	}

	/**
	 * Returns version.
	 *
	 * The version number indicates the version of the entity. The version is incremented whenever the entity is changed.
	 *
	 * @return int
	 */
	public function getVersion() {
		return $this->version;
	}

	/**
	 * Sets version.
	 *
	 * @param int $version
	 * @return TransactionVoid
	 */
	protected function setVersion($version) {
		$this->version = $version;

		return $this;
	}

	/**
	 * Validates the model's properties and throws a ValidationException if the validation fails.
	 *
	 * @throws ValidationException
	 */
	public function validate() {
		parent::validate();

		$allowed_values = array("ONLINE", "OFFLINE");
		if (!in_array($this->getMode(), $allowed_values)) {
			throw new ValidationException("invalid value for 'mode', must be one of #{allowed_values}.", 'mode', $this);
		}

		$allowed_values = array("CREATE", "PENDING", "FAILED", "SUCCESSFUL");
		if (!in_array($this->getState(), $allowed_values)) {
			throw new ValidationException("invalid value for 'state', must be one of #{allowed_values}.", 'state', $this);
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

