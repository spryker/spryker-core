<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Reader;

use Spryker\Zed\DynamicEntity\DynamicEntityConfig;

class DisallowedTablesReader implements DisallowedTablesReaderInterface
{
    /**
     * @var \Spryker\Zed\DynamicEntity\DynamicEntityConfig
     */
    protected $dynamicEntityConfig;

    /**
     * @param \Spryker\Zed\DynamicEntity\DynamicEntityConfig $dynamicEntityConfig
     */
    public function __construct(DynamicEntityConfig $dynamicEntityConfig)
    {
        $this->dynamicEntityConfig = $dynamicEntityConfig;
    }

    /**
     * @return array<string>
     */
    public function getDisallowedTables(): array
    {
        return $this->dynamicEntityConfig->getDisallowedTables() + $this->getDefaultDisallowedTables();
    }

    /**
     * @return array<string>
     */
    protected function getDefaultDisallowedTables(): array
    {
        return [
            'spy_acl_entity_rule',
            'spy_acl_entity_segment',
            'spy_acl_entity_segment_merchant',
            'spy_acl_entity_segment_merchant_user',
            'spy_acl_group',
            'spy_acl_group_archive',
            'spy_acl_groups_has_roles',
            'spy_acl_role',
            'spy_acl_role_archive',
            'spy_acl_rule',
            'spy_acl_rule_archive',
            'spy_acl_user_has_group',
            'spy_api_key',
            'spy_auth_reset_password',
            'spy_auth_reset_password_archive',
            'spy_customer',
            'spy_dynamic_entity_configuration',
            'spy_dynamic_entity_configuration_relation',
            'spy_dynamic_entity_configuration_relation_field_mapping',
            'spy_gift_card_balance_log',
            'spy_nopayment_paid',
            'spy_oauth_access_token',
            'spy_oauth_client',
            'spy_oauth_client_access_token_cache',
            'spy_oauth_code_flow_auth_code',
            'spy_oauth_refresh_token',
            'spy_payment_payone',
            'spy_payment_payone_api_call_log',
            'spy_payment_payone_api_log',
            'spy_payment_payone_detail',
            'spy_payment_payone_order_item',
            'spy_payment_payone_transaction_status_log',
            'spy_payment_payone_transaction_status_log_order_item',
            'spy_sales_order_invoice',
            'spy_sales_payment',
            'spy_user',
            'spy_user_archive',
            'spy_vault_deposit',
        ];
    }
}
