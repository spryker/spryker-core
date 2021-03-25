<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\DataProvider;

use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\EventTriggerForm;

class EventTriggerFormDataProvider
{
    protected const SUBMIT_BUTTON_CLASS = 'btn btn-primary btn-sm trigger-event';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Controller\OmsTriggerController::URL_PARAM_MERCHANT_SALES_ORDER_REFERENCE
     */
    protected const URL_PARAM_MERCHANT_SALES_ORDER_REFERENCE = 'merchant-sales-order-reference';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Controller\OmsTriggerController::URL_PARAM_REDIRECT
     */
    protected const URL_PARAM_REDIRECT = 'redirect';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Controller\OmsTriggerController::URL_PARAM_EVENT
     */
    protected const URL_PARAM_EVENT = 'event';

    /**
     * @phpstan-return array<string, mixed>
     *
     * @param string $merchantSalesOrderReference
     * @param string $event
     * @param string $redirect
     *
     * @return array
     */
    public function getOptions(
        string $merchantSalesOrderReference,
        string $event,
        string $redirect
    ): array {
        return [
            EventTriggerForm::OPTION_EVENT => $event,
            EventTriggerForm::OPTION_ACTION_QUERY_PARAMETERS => [
                static::URL_PARAM_MERCHANT_SALES_ORDER_REFERENCE => $merchantSalesOrderReference,
                static::URL_PARAM_EVENT => $event,
                static::URL_PARAM_REDIRECT => $redirect,
            ],
            EventTriggerForm::OPTION_SUBMIT_BUTTON_CLASS => static::SUBMIT_BUTTON_CLASS,
        ];
    }
}
