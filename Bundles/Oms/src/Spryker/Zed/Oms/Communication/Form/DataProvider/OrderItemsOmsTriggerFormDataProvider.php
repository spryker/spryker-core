<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Form\DataProvider;

use Spryker\Zed\Oms\Communication\Form\OmsTriggerForm;

class OrderItemsOmsTriggerFormDataProvider
{
    protected const QUERY_PARAM_ITEMS = 'items';

    /**
     * @param string $redirectUrl
     * @param string $event
     * @param int[] $salesOrderItemIds
     *
     * @return array
     */
    public function getOptions(string $redirectUrl, string $event, array $salesOrderItemIds): array
    {
        return [
            OmsTriggerForm::OPTION_OMS_ACTION => AbstractOmsTriggerFormDataProvider::OMS_ACTION_ITEM_TRIGGER,
            OmsTriggerForm::OPTION_SUBMIT_BUTTON_CLASS => AbstractOmsTriggerFormDataProvider::SUBMIT_BUTTON_CLASS,
            OmsTriggerForm::OPTION_EVENT => $event,
            OmsTriggerForm::OPTION_QUERY_PARAMS => [
                AbstractOmsTriggerFormDataProvider::QUERY_PARAM_EVENT => $event,
                AbstractOmsTriggerFormDataProvider::QUERY_PARAM_REDIRECT => $redirectUrl,
                static::QUERY_PARAM_ITEMS => $salesOrderItemIds,
            ],
        ];
    }
}
