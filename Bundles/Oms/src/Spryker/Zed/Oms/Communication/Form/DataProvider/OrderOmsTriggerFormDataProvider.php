<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Form\DataProvider;

use Spryker\Zed\Oms\Communication\Form\OmsTriggerForm;

class OrderOmsTriggerFormDataProvider extends AbstractOmsTriggerFormDataProvider
{
    /**
     * @param string $redirectUrl
     * @param string $event
     * @param int $idSalesOrder
     * @param string $submitButtonExtraClass
     *
     * @return array
     */
    public function getOptions(string $redirectUrl, string $event, int $idSalesOrder, string $submitButtonExtraClass): array
    {
        $submitButtonExtraCssClass = $submitButtonExtraClass === '' ? '' : ' ' . $submitButtonExtraClass;

        return [
            OmsTriggerForm::OPTION_OMS_ACTION => static::OMS_ACTION_ORDER_TRIGGER,
            OmsTriggerForm::OPTION_EVENT => $event,
            OmsTriggerForm::OPTION_SUBMIT_BUTTON_CLASS => static::SUBMIT_BUTTON_CLASS . $submitButtonExtraCssClass,
            OmsTriggerForm::OPTION_QUERY_PARAMS => [
                static::QUERY_PARAM_EVENT => $event,
                static::QUERY_PARAM_ID_SALES_ORDER => $idSalesOrder,
                static::QUERY_PARAM_REDIRECT => $redirectUrl,
            ],
        ];
    }
}
