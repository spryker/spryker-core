<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\DataProvider;

use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\EventTriggerForm;

class EventTriggerFormDataProvider
{
    protected const SUBMIT_BUTTON_CLASS = 'btn btn-primary btn-sm trigger-event';

    protected const URL_PARAM_ID_MERCHANT_SALES_ORDER = 'id-merchant-sales-order';
    protected const URL_PARAM_REDIRECT = 'redirect';
    protected const URL_PARAM_EVENT = 'event';

    /**
     * @phpstan-return array<string, mixed>
     *
     * @param int $idMerchantSalesOrder
     * @param string $event
     * @param string $redirect
     *
     * @return array
     */
    public function getOptions(
        int $idMerchantSalesOrder,
        string $event,
        string $redirect
    ): array {
        return [
            EventTriggerForm::OPTION_EVENT => $event,
            EventTriggerForm::OPTION_ACTION_QUERY_PARAMETERS => [
                static::URL_PARAM_ID_MERCHANT_SALES_ORDER => $idMerchantSalesOrder,
                static::URL_PARAM_EVENT => $event,
                static::URL_PARAM_REDIRECT => $redirect,
            ],
            EventTriggerForm::OPTION_SUBMIT_BUTTON_CLASS => static::SUBMIT_BUTTON_CLASS,
        ];
    }
}
