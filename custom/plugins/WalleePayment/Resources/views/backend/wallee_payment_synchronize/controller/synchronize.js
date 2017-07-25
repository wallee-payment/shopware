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

//{namespace name=backend/wallee_payment/main}
//{block name="backend/wallee_payment_synchronize/controller/main"}
Ext.define('Shopware.apps.WalleePaymentSynchronize.controller.Synchronize', {

    extend: 'Enlight.app.Controller',
    
    snippets: {
        infoMessages: {
            success: '{s name=synchronize/messages/success}Synchronization successful{/s}',
            noPermission: '{s name=synchronize/messages/no_permission}You do not have the permission to synchronize{/s}'
        },
        growlTitle: '{s name=growl_title}Wallee Payment{/s}'
    },

    directSynchronize: function() {
        var me = this,
            action = me.subApplication.action;

        /*{if !{acl_is_allowed privilege=clear}}*/
        Shopware.Notification.createGrowlMessage(
            me.snippets.growlTitle,
            me.snippets.infoMessages.noPermission
        );
        /*{else}*/
        Ext.Ajax.request({
            url: '{url controller=WalleePaymentSynchronize action=synchronize}',
            success: function(response) {
                var responseObj = Ext.JSON.decode(response.responseText),
                    message;
                if (responseObj.success) {
                    message = me.snippets.infoMessages.success;
                } else {
                    message = responseObj.message;
                }
                
                Shopware.Notification.createGrowlMessage(
                    me.snippets.growlTitle,
                    message
                );

                me.subApplication.handleSubAppDestroy(null);
            }
        });
        /*{/if}*/
    }
});
//{/block}