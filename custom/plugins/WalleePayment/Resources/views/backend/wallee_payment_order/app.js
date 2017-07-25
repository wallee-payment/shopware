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

//{block name="backend/order/application" append}
    //{include file="backend/wallee_payment_index/components/CTemplate.js"}
    //{include file="backend/wallee_payment_index/components/ComponentColumn.js"}
	//{include file="backend/wallee_payment_transaction/controller/refund.js"}
    //{include file="backend/wallee_payment_transaction/controller/transaction.js"}
	//{include file="backend/wallee_payment_transaction/view/transaction/transaction.js"}
	//{include file="backend/wallee_payment_transaction/view/transaction/details.js"}
    //{include file="backend/wallee_payment_transaction/view/transaction/line_items.js"}
    //{include file="backend/wallee_payment_transaction/view/transaction/line_items/grid.js"}
    //{include file="backend/wallee_payment_transaction/view/transaction/refunds.js"}
    //{include file="backend/wallee_payment_transaction/view/transaction/refunds/grid.js"}
    //{include file="backend/wallee_payment_transaction/view/transaction/refunds/details.js"}
    //{include file="backend/wallee_payment_transaction/model/transaction.js"}
    //{include file="backend/wallee_payment_transaction/model/line_item.js"}
    //{include file="backend/wallee_payment_transaction/model/refund_line_item.js"}
    //{include file="backend/wallee_payment_transaction/model/refund.js"}
	//{include file="backend/wallee_payment_transaction/store/transaction.js"}

	Ext.define('Shopware.apps.Order.WalleePaymentTransaction', {
		
	    override: 'Shopware.apps.Order',
		
		launch: function() {
			var me = this;
			me.getController('Shopware.apps.WalleePaymentTransaction.controller.Transaction');
			me.getController('Shopware.apps.WalleePaymentTransaction.controller.Refund');
			return me.callParent(arguments);
		}
	
	});
//{/block}