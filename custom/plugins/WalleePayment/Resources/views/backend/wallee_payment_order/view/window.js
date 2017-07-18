/**
 * This view extends the order window with a order transactions tab.
 * 
 * @author Simon Schurter
 */
//{block name="backend/order/view/detail/window" append}
//{namespace name=backend/wallee_payment/main}
Ext.define('Shopware.apps.Order.PluginWalleePayment.view.window.TransactionTab', {
    
	/**
     * Return order transaction tab.
     * 
     * @return Ext.container.Container
     */
    createOrderTransactionTab: function(parent) {
        var me = this;
        
        var tabTransactionStore = Ext.create('Shopware.apps.WalleePaymentTransaction.store.Transaction');
        tabTransactionStore.load({
            params: {
                orderId: parent.record.get('id')
            }
        });
        
        me.transactionDetails = Ext.create('Shopware.apps.WalleePaymentTransaction.view.transaction.Transaction', {
        	region: 'center'
        });
        
        parent.orderTransactionsTab = Ext.create('Ext.container.Container', {
            title: '{s name=order_view/tab/title}Wallee Payment{/s}',
            disabled: parent.record.get('id') === null,
            layout: 'border',
            items: [
                me.transactionDetails
            ],
            listeners: {
                activate: function() {
                    var record = tabTransactionStore.first();
                    if (record && !me.transactionDetails.record) {
                        me.transactionDetails.setRecord(record);
                    }
                	parent.fireEvent('orderTransactionsTabActivated', parent);
                }
            }
        });
 
        return parent.orderTransactionsTab;
    }

});

Ext.define('Shopware.apps.Order.WalleePayment.view.Window', {
    
    override: 'Shopware.apps.Order.view.detail.Window',
    
    alias: 'widget.wallee-payment-order-transaction-window',
 
    /**
     * @Override
     * Create the main tab panel which displays the different tabs.
     *
     * @return Ext.tab.Panel
     */
    createTabPanel: function() {
        var me = this, result;
 
        result = me.callParent(arguments);
        
        if (me.record.get('wallee_payment') == true) {
        	me.transactionTab = Ext.create('Shopware.apps.Order.PluginWalleePayment.view.window.TransactionTab');
        	result.add(me.transactionTab.createOrderTransactionTab(me));
        }
 
        return result;
    },
    
    updateRecord: function(record) {
    	var me = this;
    	me.transactionTab.transactionDetails.updateRecord(record);
    	
    	me.loadOrder(function(order){
    	    me.down('order-detail-panel').fireEvent('updateForms', order, me);
    	});
    },
    
    loadOrder: function(_callback){
        var me = this,
            orderStore = Ext.create('Shopware.apps.Order.store.Order');
        orderStore.load({
            id: me.record.get('id'),
            callback: function(records, operation, success){
                if (success) {
                    _callback(records[0]);
                }
            }
        });
    }
    
});
//{/block}