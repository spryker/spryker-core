<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\DataProvider;

class EventItemTriggerFormDataProvider
{
    protected const SUBMIT_BUTTON_CLASS = 'btn btn-primary btn-sm trigger-order-single-event';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Controller\OmsTriggerController::URL_PARAM_MERCHANT_SALES_ORDER_ITEM_REFERENCE
     */
    protected const URL_PARAM_MERCHANT_SALES_ORDER_ITEM_REFERENCE = 'merchant-sales-order-item-reference';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Controller\OmsTriggerController::URL_PARAM_REDIRECT
     */
    protected const URL_PARAM_REDIRECT = 'redirect';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Controller\OmsTriggerController::URL_PARAM_EVENT_NAME
     */
    protected const URL_PARAM_EVENT_NAME = 'event';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\EventTriggerForm::OPTION_SUBMIT_BUTTON_CLASS
     */
    protected const OPTION_SUBMIT_BUTTON_CLASS = 'OPTION_SUBMIT_BUTTON_CLASS';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\EventItemTriggerForm::OPTION_EVENT
     */
    protected const OPTION_EVENT = 'OPTION_EVENT';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\EventItemTriggerForm::OPTION_ACTION_QUERY_PARAMETERS
     */
    protected const OPTION_ACTION_QUERY_PARAMETERS = 'OPTION_ACTION_QUERY_PARAMETERS';

    /**
     * @phpstan-return array<int|string, mixed>
     *
     * @param string $merchantSalesOrderItemReference
     * @param string $eventName
     * @param string $redirect
     *
     * @return array
     */
    public function getOptions(
        string $merchantSalesOrderItemReference,
        string $eventName,
        string $redirect
    ): array {
        return [
            static::OPTION_EVENT => $eventName,
            static::OPTION_ACTION_QUERY_PARAMETERS => [
                static::URL_PARAM_MERCHANT_SALES_ORDER_ITEM_REFERENCE => $merchantSalesOrderItemReference,
                static::URL_PARAM_EVENT_NAME => $eventName,
                static::URL_PARAM_REDIRECT => $redirect,
            ],
            static::OPTION_SUBMIT_BUTTON_CLASS => static::SUBMIT_BUTTON_CLASS,
        ];
    }
}
