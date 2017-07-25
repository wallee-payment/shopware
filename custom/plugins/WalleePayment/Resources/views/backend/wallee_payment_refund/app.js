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

//{block name="backend/wallee_payment_refund/application"}
    //{include file="backend/wallee_payment_index/components/CTemplate.js"}
    //{include file="backend/wallee_payment_index/components/ComponentColumn.js"}

    Ext.define('Shopware.apps.WalleePaymentRefund', {
        
        extend: 'Enlight.app.SubApplication',
        
        name: 'Shopware.apps.WalleePaymentRefund',
        
        loadPath: '{url controller="WalleePaymentRefund" action=load}',
        
        controllers: [
            'Main'
        ],
        
        launch: function() {
            var me = this,
                mainController = me.getController('Main');
            return mainController.mainWindow;
        }
        
    });
//{/block}