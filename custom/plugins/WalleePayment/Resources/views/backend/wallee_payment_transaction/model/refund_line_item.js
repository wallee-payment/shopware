//{block name="backend/wallee_payment_transaction/model/refund_line_item"}
Ext.define('Shopware.apps.WalleePaymentTransaction.model.RefundLineItem', {
    
    extend: 'Ext.data.Model',
    
    quantityReduction: 0,
    unitPriceReduction: 0,
 
    fields: [
        //{block name="backend/wallee_payment_transaction/model/refund_line_item/fields"}{/block}
        { name: 'id', type: 'string' },
        { name: 'uniqueId', type: 'string' },
        { name: 'sku', type: 'string' },
        { name: 'name', type: 'string' },
        { name: 'amountIncludingTax', type: 'float' },
        { name: 'unitPriceIncludingTax', type: 'float' },
        { name: 'taxes', type: 'object' },
        { name: 'taxRate', type: 'float' },
        { name: 'type', type: 'string' },
        { name: 'quantity', type: 'float' },
        { name: 'shippingRequired', type: 'boolean' }
    ]

});
//{/block}