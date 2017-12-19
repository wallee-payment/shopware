<?php

/**
 * Wallee Shopware
 *
 * This Shopware extension enables to process payments with Wallee (https://wallee.com/).
 *
 * @package Wallee_Payment
 * @author customweb GmbH (http://www.customweb.com/)
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache Software License (ASL 2.0)
 * @link https://github.com/wallee-payment/shopware
 */

namespace WalleePayment\Components;

use Shopware\Components\Model\ModelManager;
use Shopware\Components\Plugin\ConfigReader;
use Shopware\Models\Order\Order;
use Shopware\Models\Shop\Shop;
use Symfony\Component\DependencyInjection\ContainerInterface;
use WalleePayment\Components\PaymentMethodConfiguration as PaymentMethodConfigurationService;
use WalleePayment\Components\TransactionInfo as TransactionInfoService;
use WalleePayment\Models\OrderTransactionMapping;
use Wallee\Sdk\Model\EntityQuery;
use Wallee\Sdk\Model\EntityQueryFilter;
use Wallee\Sdk\Model\EntityQueryFilterType;
use Wallee\Sdk\Model\EntityQueryOrderByType;
use Wallee\Sdk\Model\TransactionState;
use Shopware\Models\Customer\Customer;

class Transaction extends AbstractService
{

    /**
     *
     * @var \Wallee\Sdk\Model\Transaction[]
     */
    private static $transactionByOrderCache = array();
    
    /**
     *
     * @var \Wallee\Sdk\Model\Transaction[]
     */
    private static $transactionByBasketCache = array();

    /**
     *
     * @var \Wallee\Sdk\Model\PaymentMethodConfiguration[]
     */
    private static $possiblePaymentMethodByOrderCache = array();
    
    /**
     *
     * @var \Wallee\Sdk\Model\PaymentMethodConfiguration[]
     */
    private static $possiblePaymentMethodByBasketCache = array();

    /**
     *
     * @var ModelManager
     */
    private $modelManager;

    /**
     *
     * @var ConfigReader
     */
    private $configReader;

    /**
     *
     * @var \Wallee\Sdk\ApiClient
     */
    private $apiClient;

    /**
     *
     * @var LineItem
     */
    private $lineItem;

    /**
     *
     * @var PaymentMethodConfigurationService
     */
    private $paymentMethodConfigurationService;
    
    /**
     *
     * @var TransactionInfoService
     */
    private $transactionInfoService;
    
    /**
     *
     * @var Session
     */
    private $sessionService;

    /**
     * The transaction API service.
     *
     * @var \Wallee\Sdk\Service\TransactionService
     */
    private $transactionService;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     * @param ModelManager $modelManager
     * @param ConfigReader $configReader
     * @param ApiClient $apiClient
     * @param LineItem $lineItem
     * @param PaymentMethodConfigurationService $paymentMethodConfigurationService
     * @param TransactionInfoService $transactionInfoService
     */
    public function __construct(ContainerInterface $container, ModelManager $modelManager, ConfigReader $configReader, ApiClient $apiClient, LineItem $lineItem, PaymentMethodConfigurationService $paymentMethodConfigurationService, TransactionInfoService $transactionInfoService, Session $sessionService)
    {
        parent::__construct($container);
        $this->container = $container;
        $this->modelManager = $modelManager;
        $this->configReader = $configReader;
        $this->apiClient = $apiClient->getInstance();
        $this->lineItem = $lineItem;
        $this->paymentMethodConfigurationService = $paymentMethodConfigurationService;
        $this->transactionInfoService = $transactionInfoService;
        $this->sessionService = $sessionService;
        $this->transactionService = new \Wallee\Sdk\Service\TransactionService($this->apiClient);
    }

    /**
     * Returns the transaction with the given id.
     *
     * @param int $spaceId
     * @param int $transactionId
     * @return \Wallee\Sdk\Model\Transaction
     */
    public function getTransaction($spaceId, $transactionId)
    {
        return $this->transactionService->read($spaceId, $transactionId);
    }

    /**
     *
     * @param int $spaceId
     * @param int $transactionId
     */
    public function handleTransactionState($spaceId, $transactionId)
    {
        $transaction = $this->getTransaction($spaceId, $transactionId);
        $this->container->get('wallee_payment.subscriber.webhook.transaction')->process($transaction);
    }

    /**
     * Returns the transaction's latest line item version.
     *
     * @param int $spaceId
     * @param int $transactionId
     * @return \Wallee\Sdk\Model\TransactionLineItemVersion
     */
    public function getLineItemVersion($spaceId, $transactionId)
    {
        return $this->transactionService->getLatestTransactionLineItemVersion($spaceId, $transactionId);
    }

    /**
     * Updates the line items of the given transaction.
     *
     * @param int $spaceId
     * @param int $transactionId
     * @param \Wallee\Sdk\Model\LineItem[] $lineItems
     * @return \Wallee\Sdk\Model\TransactionLineItemVersion
     */
    public function updateLineItems($spaceId, $transactionId, $lineItems)
    {
        $updateRequest = new \Wallee\Sdk\Model\TransactionLineItemUpdateRequest();
        $updateRequest->setTransactionId($transactionId);
        $updateRequest->setNewLineItems($lineItems);
        return $this->transactionService->updateTransactionLineItems($spaceId, $updateRequest);
    }

    /**
     * Returns the URL to Wallee's JavaScript library that is necessary to display the payment form.
     *
     * @param Order $order
     * @return string
     */
    public function getJavaScriptUrl()
    {
        $transaction = $this->getTransactionByBasket();
        return $this->transactionService->buildJavaScriptUrl($transaction->getLinkedSpaceId(), $transaction->getId());
    }

    /**
     * Returns the payment methods that can be used with the given order.
     *
     * @param Order $order
     * @return \Wallee\Sdk\Model\PaymentMethodConfiguration[]
     */
    public function getPossiblePaymentMethods(Order $order)
    {
        if (! isset(self::$possiblePaymentMethodByOrderCache[$order->getId()]) || self::$possiblePaymentMethodByOrderCache[$order->getId()] == null) {
            $transaction = $this->getTransactionByOrder($order);
            $paymentMethods = $this->transactionService->fetchPossiblePaymentMethods($transaction->getLinkedSpaceId(), $transaction->getId());

            foreach ($paymentMethods as $paymentMethod) {
                $this->paymentMethodConfigurationService->updateData($paymentMethod);
            }

            self::$possiblePaymentMethodByOrderCache[$order->getId()] = $paymentMethods;
        }

        return self::$possiblePaymentMethodByOrderCache[$order->getId()];
    }
    
    /**
     * Returns the payment methods that can be used with the given basket.
     *
     * @param string $sessionId
     * @return \Wallee\Sdk\Model\PaymentMethodConfiguration[]
     */
    public function getPossiblePaymentMethodsByBasket()
    {
        $sessionId = $this->sessionService->getSessionId();
        if (! isset(self::$possiblePaymentMethodByBasketCache[$sessionId]) || self::$possiblePaymentMethodByBasketCache[$sessionId] == null) {
            $transaction = $this->getTransactionByBasket();
            $paymentMethods = $this->transactionService->fetchPossiblePaymentMethods($transaction->getLinkedSpaceId(), $transaction->getId());
            
            foreach ($paymentMethods as $paymentMethod) {
                $this->paymentMethodConfigurationService->updateData($paymentMethod);
            }
            
            self::$possiblePaymentMethodByBasketCache[$sessionId] = $paymentMethods;
        }
        
        return self::$possiblePaymentMethodByBasketCache[$sessionId];
    }

    /**
     * Returns the transaction for the given order.
     *
     * If no transaction exists, a new one is created.
     *
     * @param Order $order
     * @return \Wallee\Sdk\Model\Transaction
     */
    public function getTransactionByOrder(Order $order)
    {
        if (! isset(self::$transactionByOrderCache[$order->getId()]) || self::$transactionByOrderCache[$order->getId()] == null) {
            $orderTransactionMapping = $this->getOrderTransactionMapping($order);
            if ($orderTransactionMapping instanceof OrderTransactionMapping) {
                $this->updateTransaction($order, $orderTransactionMapping->getTransactionId(), $orderTransactionMapping->getSpaceId());
            } else {
                $this->createTransaction($order);
            }
        }
        return self::$transactionByOrderCache[$order->getId()];
    }
    
    /**
     * Returns the transaction for the given basket.
     *
     * If no transaction exists, a new one is created.
     *
     * @return \Wallee\Sdk\Model\Transaction
     */
    public function getTransactionByBasket()
    {
        $sessionId = $this->sessionService->getSessionId();
        if (! isset(self::$transactionByBasketCache[$sessionId]) || self::$transactionByBasketCache[$sessionId] == null) {
            $orderTransactionMapping = $this->getBasketTransactionMapping();
            if ($orderTransactionMapping instanceof OrderTransactionMapping) {
                $this->updateBasketTransaction($orderTransactionMapping->getTransactionId(), $orderTransactionMapping->getSpaceId());
            } else {
                $this->createBasketTransaction();
            }
        }
        return self::$transactionByBasketCache[$sessionId];
    }

    /**
     * Creates a transaction for the given order.
     *
     * @param Order $order
     * @return \Wallee\Sdk\Model\TransactionCreate
     */
    public function createTransaction(Order $order)
    {
        $existingTransaction = $this->findExistingTransaction($order->getShop(), $order->getCustomer());
        if ($existingTransaction instanceof \Wallee\Sdk\Model\Transaction) {
            return $this->updateTransaction($order, $existingTransaction->getId(), $existingTransaction->getLinkedSpaceId());
        } else {
            $transaction = new \Wallee\Sdk\Model\TransactionCreate();
            $transaction->setCustomersPresence(\Wallee\Sdk\Model\CustomersPresence::VIRTUAL_PRESENT);
            $this->assembleTransactionData($transaction, $order);

            $pluginConfig = $this->configReader->getByPluginName('WalleePayment', $order->getShop());
            $spaceId = $pluginConfig['spaceId'];

            $transaction = $this->transactionService->create($spaceId, $transaction);
            
            $this->updateOrCreateTransactionMapping($transaction, $order);
            self::$transactionByOrderCache[$order->getId()] = $transaction;
            return $transaction;
        }
    }
    
    /**
     * Creates a transaction for the given basket.
     *
     * @return \Wallee\Sdk\Model\TransactionCreate
     */
    public function createBasketTransaction()
    {
        $existingTransaction = $this->findExistingTransaction($this->container->get('shop'), $this->modelManager->getRepository(Customer::class)->find($this->container->get('session')->get('sUserId')));
        if ($existingTransaction instanceof \Wallee\Sdk\Model\Transaction) {
            return $this->updateBasketTransaction($existingTransaction->getId(), $existingTransaction->getLinkedSpaceId());
        } else {
            $transaction = new \Wallee\Sdk\Model\TransactionCreate();
            $transaction->setCustomersPresence(\Wallee\Sdk\Model\CustomersPresence::VIRTUAL_PRESENT);
            $this->assembleBasketTransactionData($transaction);
            
            $pluginConfig = $this->configReader->getByPluginName('WalleePayment', $this->container->get('shop'));
            $spaceId = $pluginConfig['spaceId'];
            
            $transaction = $this->transactionService->create($spaceId, $transaction);
            
            $this->updateOrCreateBasketTransactionMapping($transaction);
            self::$transactionByBasketCache[$this->sessionService->getSessionId()] = $transaction;
            return $transaction;
        }
    }
    
    private function findExistingTransaction(Shop $shop, Customer $customer)
    {
        $pluginConfig = $this->configReader->getByPluginName('WalleePayment', $shop);
        $spaceId = $pluginConfig['spaceId'];
        
        $query = new EntityQuery();
        $filter = new EntityQueryFilter();
        $filter->setType(EntityQueryFilterType::_AND);
        $filter->setChildren(array(
            $this->createEntityFilter('state', TransactionState::PENDING),
            $this->createEntityFilter('customerId', $customer->getId()),
            $this->createEntityFilter('customerEmailAddress', $customer->getEmail())
        ));
        $query->setFilter($filter);
        $query->setOrderBys([$this->createEntityOrderBy('createdOn', EntityQueryOrderByType::DESC)]);
        $query->setNumberOfEntities(1);
        $transactions = $this->transactionService->search($spaceId, $query);
        if (is_array($transactions) && !empty($transactions)) {
            return current($transactions);
        } else {
            return null;
        }
    }

    /**
     * Updates the transaction for the given order.
     *
     * If the transaction is not in pending state, a new one is created.
     *
     * @param Order $order
     * @param int $transactionId
     * @param int $spaceId
     * @param boolean $confirm
     * @return \Wallee\Sdk\Model\AbstractTransactionPending
     */
    public function updateTransaction(Order $order, $transactionId, $spaceId, $confirm = false)
    {
        $lastException = null;
        $this->apiClient->setConnectionTimeout(5);
        for ($i = 0; $i < 5; $i++) {
            try {
                $transaction = $this->transactionService->read($spaceId, $transactionId);
                if ($transaction->getState() != \Wallee\Sdk\Model\TransactionState::PENDING) {
                    $newTransaction = $this->createTransaction($order);
                    $this->apiClient->setConnectionTimeout(20);
                    return $newTransaction;
                }
                $this->transactionInfoService->updateTransactionInfoByOrder($transaction, $order);
                
                $pendingTransaction = new \Wallee\Sdk\Model\TransactionPending();
                $pendingTransaction->setId($transaction->getId());
                $pendingTransaction->setVersion($transaction->getVersion());
                $this->assembleTransactionData($pendingTransaction, $order);
                
                if ($confirm) {
                    $updatedTransaction = $this->transactionService->confirm($spaceId, $pendingTransaction);
                } else {
                    $updatedTransaction = $this->transactionService->update($spaceId, $pendingTransaction);
                }
                
                $this->updateOrCreateTransactionMapping($transaction, $order);
                self::$transactionByOrderCache[$order->getId()] = $transaction;
                $this->apiClient->setConnectionTimeout(20);
                return $updatedTransaction;
            } catch (\Wallee\Sdk\VersioningException $e) {
                // Try to update the transaction again, if a versioning exception occurred.
                $lastException = $e;
            } catch (\Wallee\Sdk\Http\ConnectionException $e) {
                // Try to update the transaction again, if a connection exception occurred.
                $lastException = $e;
            }
        }
        $this->apiClient->setConnectionTimeout(20);
        throw $lastException;
    }
    
    /**
     * Updates the transaction for the given basket.
     *
     * If the transaction is not in pending state, a new one is created.
     *
     * @param int $transactionId
     * @param int $spaceId
     * @return \Wallee\Sdk\Model\AbstractTransactionPending
     */
    public function updateBasketTransaction($transactionId, $spaceId)
    {
        for ($i = 0; $i < 5; $i++) {
            try {
                $transaction = $this->transactionService->read($spaceId, $transactionId);
                if ($transaction->getState() != \Wallee\Sdk\Model\TransactionState::PENDING) {
                    return $this->createBasketTransaction();
                }
                
                $pendingTransaction = new \Wallee\Sdk\Model\TransactionPending();
                $pendingTransaction->setId($transaction->getId());
                $pendingTransaction->setVersion($transaction->getVersion());
                $this->assembleBasketTransactionData($pendingTransaction);
                
                $updatedTransaction = $this->transactionService->update($spaceId, $pendingTransaction);
                
                $this->updateOrCreateBasketTransactionMapping($transaction);
                self::$transactionByBasketCache[$this->sessionService->getSessionId()] = $transaction;
                return $updatedTransaction;
            } catch (\Wallee\Sdk\VersioningException $e) {
                // Try to update the transaction again, if a versioning exception occurred.
            }
        }
        throw new \Wallee\Sdk\VersioningException();
    }
    
    /**
     * Assembles the transaction data for the given order.
     *
     * @param \Wallee\Sdk\Model\AbstractTransactionPending $transaction
     * @param Order $order
     */
    private function assembleTransactionData(\Wallee\Sdk\Model\AbstractTransactionPending $transaction, Order $order)
    {
        if ($order->getNumber() != '0') {
            $transaction->setMerchantReference($order->getNumber());
        }
        $transaction->setCurrency($order->getCurrency());
        $transaction->setBillingAddress($this->getBillingAddress($order->getCustomer()));
        $transaction->setShippingAddress($this->getShippingAddress($order->getCustomer()));
        $transaction->setCustomerEmailAddress($order->getCustomer()
            ->getEmail());
        $transaction->setCustomerId($order->getCustomer()
            ->getId());
        $transaction->setLanguage($order->getLanguageSubShop()
            ->getLocale()
            ->getLocale());
        if ($order->getDispatch() instanceof \Shopware\Models\Dispatch\Dispatch) {
            $transaction->setShippingMethod($this->fixLength($order->getDispatch()
                ->getName(), 200));
        }

        $pluginConfig = $this->configReader->getByPluginName('WalleePayment', $order->getShop());
        $spaceViewId = $pluginConfig['spaceViewId'];
        
        if ($transaction instanceof \Wallee\Sdk\Model\TransactionCreate) {
            $transaction->setSpaceViewId($spaceViewId);
            $transaction->setAutoConfirmationEnabled(false);
        }

        $transaction->setLineItems($this->lineItem->collectLineItems($order));
        $transaction->setAllowedPaymentMethodConfigurations([]);
        
        if (!($transaction instanceof \Wallee\Sdk\Model\TransactionCreate)) {
            $transaction->setSuccessUrl($this->getUrl('WalleePaymentTransaction', 'success', null, null, ['spaceId' => $pluginConfig['spaceId'], 'transactionId' => $transaction->getId()]));
            $transaction->setFailedUrl($this->getUrl('WalleePaymentTransaction', 'failure', null, null, ['spaceId' => $pluginConfig['spaceId'], 'transactionId' => $transaction->getId()]));
        }
    }
    
    /**
     * Assembles the transaction data for the given basket.
     *
     * @param \Wallee\Sdk\Model\AbstractTransactionPending $transaction
     */
    private function assembleBasketTransactionData(\Wallee\Sdk\Model\AbstractTransactionPending $transaction)
    {
        /* @var Shop $shop */
        $shop = $this->container->get('shop');
        
        /* @var Customer $customer */
        $customer = $this->modelManager->getRepository(Customer::class)->find($this->container->get('session')->get('sUserId'));
        
        $transaction->setCurrency(Shopware()->Modules()->System()->sCurrency['currency']);
        $transaction->setBillingAddress($this->getBillingAddress($customer));
        $transaction->setShippingAddress($this->getShippingAddress($customer));
        $transaction->setCustomerEmailAddress($customer
            ->getEmail());
        $transaction->setCustomerId($customer
            ->getId());
        $transaction->setLanguage($shop->getLocale()->getLocale());
        
        $pluginConfig = $this->configReader->getByPluginName('WalleePayment', $shop);
        $spaceViewId = $pluginConfig['spaceViewId'];
        
        if ($transaction instanceof \Wallee\Sdk\Model\TransactionCreate) {
            $transaction->setSpaceViewId($spaceViewId);
        }
        
        $transaction->setLineItems($this->lineItem->collectBasketLineItems());
        $transaction->setAllowedPaymentMethodConfigurations([]);
        
        if (!($transaction instanceof \Wallee\Sdk\Model\TransactionCreate)) {
            $transaction->setSuccessUrl($this->getUrl('WalleePaymentTransaction', 'success', null, null, ['spaceId' => $pluginConfig['spaceId'], 'transactionId' => $transaction->getId()]));
            $transaction->setFailedUrl($this->getUrl('WalleePaymentTransaction', 'failure', null, null, ['spaceId' => $pluginConfig['spaceId'], 'transactionId' => $transaction->getId()]));
        }
    }

    private function getBillingAddress(Customer $customer)
    {
        $billingAddressId = $this->container->get('session')->offsetGet('checkoutBillingAddressId', null);
        if (empty($billingAddressId)) {
            $billingAddressId = $customer
                ->getDefaultBillingAddress()
                ->getId();
        }
        $billingAddress = $this->modelManager->getRepository(\Shopware\Models\Customer\Address::class)->getOneByUser($billingAddressId, $customer
            ->getId());

        $address = $this->getAddress($billingAddress);
        if ($customer->getBirthday() instanceof \DateTime && $customer->getBirthday() != new \DateTime('0000-00-00')) {
            $address->setDateOfBirth($customer
                ->getBirthday()
                ->format(\DateTime::W3C));
        }
        $address->setEmailAddress($customer
            ->getEmail());
        return $address;
    }

    private function getShippingAddress(Customer $customer)
    {
        $shippingAddressId = $this->container->get('session')->offsetGet('checkoutShippingAddressId', null);
        if (empty($shippingAddressId)) {
            $shippingAddressId = $customer
                ->getDefaultShippingAddress()
                ->getId();
        }
        $shippingAddress = $this->modelManager->getRepository(\Shopware\Models\Customer\Address::class)->getOneByUser($shippingAddressId, $customer
            ->getId());

        $address = $this->getAddress($shippingAddress);
        $address->setEmailAddress($customer
            ->getEmail());
        return $address;
    }

    private function getAddress(\Shopware\Models\Customer\Address $customerAddress)
    {
        $address = new \Wallee\Sdk\Model\AddressCreate();
        $address->setSalutation($this->fixLength($customerAddress->getSalutation(), 20));
        $address->setCity($this->fixLength($customerAddress->getCity(), 100));
        $address->setCountry($customerAddress->getCountry()
            ->getIso());
        $address->setFamilyName($this->fixLength($customerAddress->getLastName(), 100));
        $address->setGivenName($this->fixLength($customerAddress->getFirstName(), 100));
        $address->setOrganizationName($this->fixLength($customerAddress->getCompany(), 100));
        $address->setPhoneNumber($customerAddress->getPhone());
        if ($customerAddress->getState() instanceof \Shopware\Models\Country\State) {
            $address->setPostalState($customerAddress->getState()
                ->getShortCode());
        }
        $address->setPostCode($this->fixLength($customerAddress->getZipCode(), 40));
        $address->setStreet($this->fixLength($customerAddress->getStreet(), 300));
        $address->setSalesTaxNumber($this->fixLength($customerAddress->getVatId(), 100));
        return $address;
    }

    /**
     *
     * @param Order $order
     * @return OrderTransactionMapping
     */
    private function getOrderTransactionMapping(Order $order)
    {
        $filter = [
            'orderId' => $order->getId()
        ];
        if ($order->getTemporaryId() != null) {
            $filter = [
                'temporaryId' => $order->getTemporaryId()
            ];
        }
        return $this->modelManager->getRepository(OrderTransactionMapping::class)->findOneBy($filter);
    }
    
    /**
     *
     * @return OrderTransactionMapping
     */
    private function getBasketTransactionMapping()
    {
        $filter = [
            'temporaryId' => $this->sessionService->getSessionId()
        ];
        return $this->modelManager->getRepository(OrderTransactionMapping::class)->findOneBy($filter);
    }
    
    private function updateOrCreateTransactionMapping(\Wallee\Sdk\Model\Transaction $transaction, Order $order)
    {
        if ($order->getTemporaryId() != null) {
            /* @var OrderTransactionMapping $orderTransactionMapping */
            $orderTransactionMappings = $this->modelManager->getRepository(OrderTransactionMapping::class)->findBy([
                'temporaryId' => $order->getTemporaryId()
            ]);
            foreach ($orderTransactionMappings as $mapping) {
                $this->modelManager->remove($mapping);
            }
            $this->modelManager->flush();
        }
        
        /* @var OrderTransactionMapping $orderTransactionMapping */
        $orderTransactionMappings = $this->modelManager->getRepository(OrderTransactionMapping::class)->findBy([
            'transactionId' => $transaction->getId(),
            'spaceId' => $transaction->getLinkedSpaceId()
        ]);
        if (count($orderTransactionMappings) > 1) {
            foreach ($orderTransactionMappings as $mapping) {
                $this->modelManager->remove($mapping);
            }
            $this->modelManager->flush();
            $orderTransactionMapping = null;
        } else {
            $orderTransactionMapping = current($orderTransactionMappings);
        }
        
        if (!($orderTransactionMapping instanceof OrderTransactionMapping)) {
            $orderTransactionMapping = new OrderTransactionMapping();
            $orderTransactionMapping->setSpaceId($transaction->getLinkedSpaceId());
            $orderTransactionMapping->setTransactionId($transaction->getId());
        }
        $orderTransactionMapping->setOrder($order);
        $this->modelManager->persist($orderTransactionMapping);
        $this->modelManager->flush($orderTransactionMapping);
    }
    
    private function updateOrCreateBasketTransactionMapping(\Wallee\Sdk\Model\Transaction $transaction)
    {
        /* @var OrderTransactionMapping $orderTransactionMapping */
        $orderTransactionMappings = $this->modelManager->getRepository(OrderTransactionMapping::class)->findBy([
            'temporaryId' => $this->sessionService->getSessionId()
        ]);
        foreach ($orderTransactionMappings as $mapping) {
            $this->modelManager->remove($mapping);
        }
        $this->modelManager->flush();
        
        /* @var OrderTransactionMapping $orderTransactionMapping */
        $orderTransactionMappings = $this->modelManager->getRepository(OrderTransactionMapping::class)->findBy([
            'transactionId' => $transaction->getId(),
            'spaceId' => $transaction->getLinkedSpaceId()
        ]);
        if (count($orderTransactionMappings) > 1) {
            foreach ($orderTransactionMappings as $mapping) {
                $this->modelManager->remove($mapping);
            }
            $this->modelManager->flush();
            $orderTransactionMapping = null;
        } else {
            $orderTransactionMapping = current($orderTransactionMappings);
        }
        
        if (!($orderTransactionMapping instanceof OrderTransactionMapping)) {
            $orderTransactionMapping = new OrderTransactionMapping();
            $orderTransactionMapping->setSpaceId($transaction->getLinkedSpaceId());
            $orderTransactionMapping->setTransactionId($transaction->getId());
        }
        $orderTransactionMapping->setTemporaryId($this->sessionService->getSessionId());
        $orderTransactionMapping->setShop($this->container->get('shop'));
        $this->modelManager->persist($orderTransactionMapping);
        $this->modelManager->flush($orderTransactionMapping);
    }
}
