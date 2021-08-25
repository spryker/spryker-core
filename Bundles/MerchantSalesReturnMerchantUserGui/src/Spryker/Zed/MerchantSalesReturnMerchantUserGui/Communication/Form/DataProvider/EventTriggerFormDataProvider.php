<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\DataProvider;

class EventTriggerFormDataProvider
{
    protected const SUBMIT_BUTTON_CLASS = 'btn btn-primary btn-sm trigger-event';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Controller\OmsTriggerController::URL_PARAM_RETURN_REFERENCE
     */
    protected const URL_PARAM_RETURN_REFERENCE = 'return-reference';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Controller\OmsTriggerController::URL_PARAM_REDIRECT
     */
    protected const URL_PARAM_REDIRECT = 'redirect';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Controller\OmsTriggerController::URL_PARAM_EVENT
     */
    protected const URL_PARAM_EVENT = 'event';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\EventTriggerForm::OPTION_EVENT
     */
    protected const OPTION_EVENT = 'OPTION_EVENT';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\EventTriggerForm::OPTION_SUBMIT_BUTTON_CLASS
     */
    protected const OPTION_SUBMIT_BUTTON_CLASS = 'OPTION_SUBMIT_BUTTON_CLASS';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\EventTriggerForm::OPTION_ACTION_QUERY_PARAMETERS
     */
    protected const OPTION_ACTION_QUERY_PARAMETERS = 'OPTION_ACTION_QUERY_PARAMETERS';

    /**
     * @phpstan-return array<int|string, mixed>
     *
     * @param string $returnReference
     * @param string $event
     * @param string $redirect
     *
     * @return array
     */
    public function getOptions(
        string $returnReference,
        string $event,
        string $redirect
    ): array {
        return [
            static::OPTION_EVENT => $event,
            static::OPTION_ACTION_QUERY_PARAMETERS => [
                static::URL_PARAM_RETURN_REFERENCE => $returnReference,
                static::URL_PARAM_EVENT => $event,
                static::URL_PARAM_REDIRECT => $redirect,
            ],
            static::OPTION_SUBMIT_BUTTON_CLASS => static::SUBMIT_BUTTON_CLASS,
        ];
    }
}
