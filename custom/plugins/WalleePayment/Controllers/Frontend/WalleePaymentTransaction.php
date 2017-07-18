<?php

use WalleePayment\Models\TransactionInfo;
use WalleePayment\Components\Controller\Frontend;

class Shopware_Controllers_Frontend_WalleePaymentTransaction extends Frontend
{

    /**
     * @var sAdmin
     */
    private $admin;

    /**
     * Init controller method
     */
    public function init()
    {
        $this->admin = Shopware()->Modules()->Admin();
    }

    public function preDispatch()
    {
        parent::preDispatch();

        if (!in_array($this->Request()->getActionName(), array('success', 'failure'))
            && !$this->admin->sCheckUser()) {
            return $this->forward('index', 'register');
        }
    }

    public function successAction()
    {
        $this->get('modules')->Order()->sDeleteTemporaryOrder();
        $this->get('wallee_payment.basket')->deleteBasket();
        $this->redirect([
            'controller' => 'checkout',
            'action' => 'finish'
        ]);
    }

    public function failureAction()
    {
        $spaceId = $this->Request()->getParam('spaceId');
        $transactionId = $this->Request()->getParam('transactionId');
        if (!empty($spaceId) && !empty($transactionId)) {
            /* @var TransactionInfo $transactionInfo */
            $transactionInfo = $this->getModelManager()
                ->getRepository(TransactionInfo::class)
                ->findOneBy([
                    'spaceId' => $spaceId,
                    'transactionId' => $transactionId
                ]);
            if ($transactionInfo instanceof TransactionInfo) {
                /* @var \Enlight_Components_Session_Namespace $session */
                $session = $this->get('session');
                $session['wallee_payment.failed_transaction'] = $transactionInfo->getId();
            }
        }

        $this->redirect([
            'controller' => 'checkout',
            'action' => 'confirm'
        ]);
    }

    public function downloadInvoiceAction()
    {
        /* @var TransactionInfo $transactionInfo */
        $transactionInfo = $this->getModelManager()
        ->getRepository(TransactionInfo::class)
        ->find($this->Request()->getParam('id'));

        if (!$this->isAllowed($transactionInfo)) {
            return $this->redirect(['controller' => 'account', 'action' => 'orders']);
        }

        $service = new \Wallee\Sdk\Service\TransactionService($this->get('wallee_payment.api_client')->getInstance());
        $document = $service->getInvoiceDocument($transactionInfo->getSpaceId(), $transactionInfo->getTransactionId());
        $this->download($document);
    }

    public function downloadPackingSlipAction()
    {
        /* @var TransactionInfo $transactionInfo */
        $transactionInfo = $this->getModelManager()
            ->getRepository(TransactionInfo::class)
            ->find($this->Request()->getParam('id'));

        if (!$this->isAllowed($transactionInfo)) {
            return $this->redirect(['controller' => 'account', 'action' => 'orders']);
        }

        $service = new \Wallee\Sdk\Service\TransactionService($this->get('wallee_payment.api_client')->getInstance());
        $document = $service->getPackingSlip($transactionInfo->getSpaceId(), $transactionInfo->getTransactionId());
        $this->download($document);
    }

    /**
     *
     * @param TransactionInfo $transactionInfo
     * @return boolean
     */
    private function isAllowed(TransactionInfo $transactionInfo)
    {
        return $transactionInfo->getOrder() != null && $transactionInfo->getOrder()->getCustomer()->getId() == Shopware()->Session()->sUserId;
    }
}