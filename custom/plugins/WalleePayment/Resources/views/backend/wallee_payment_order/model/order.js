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
//{block name="backend/order/model/order/fields"}
//{$smarty.block.parent}
	{ name: 'wallee_payment', type: 'boolean' },
//{/block}