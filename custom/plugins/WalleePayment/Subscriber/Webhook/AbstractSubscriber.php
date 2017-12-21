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

namespace WalleePayment\Subscriber\Webhook;

use Enlight\Event\SubscriberInterface;

abstract class AbstractSubscriber implements SubscriberInterface
{
    
    /**
     * In case a \Wallee\Sdk\Http\ConnectionException or a \Wallee\Sdk\VersioningException occurs, the {@code $callback} function is called again.
     *
     * @param \Wallee\Sdk\ApiClient $apiClient
     * @param function $callback
     * @throws \Wallee\Sdk\Http\ConnectionException
     * @throws \Wallee\Sdk\VersioningException
     * @return mixed
     */
    protected function callApi(\Wallee\Sdk\ApiClient $apiClient, $callback) {
        $lastException = null;
        $apiClient->setConnectionTimeout(5);
        for ($i = 0; $i < 5; $i++) {
            try {
                return $callback();
            } catch (\Wallee\Sdk\VersioningException $e) {
                $lastException = $e;
            } catch (\Wallee\Sdk\Http\ConnectionException $e) {
                $lastException = $e;
            } finally {
                $apiClient->setConnectionTimeout(20);
            }
        }
        throw $lastException;
    }
    
}
