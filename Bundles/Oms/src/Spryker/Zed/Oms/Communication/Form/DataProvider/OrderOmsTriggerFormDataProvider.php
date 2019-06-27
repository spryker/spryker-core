<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Form\DataProvider;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Oms\Communication\Form\OmsTriggerForm;

class OrderOmsTriggerFormDataProvider extends InitialOmsTriggerFormDataProvider
{
    public const ROUTE_REDIRECT = '/sales/detail';

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $event
     *
     * @return array
     */
    public function getOrderOmsTriggerFormOptions(OrderTransfer $orderTransfer, string $event): array
    {
        $idSalesOrder = $orderTransfer->getIdSalesOrder();
        $redirectUrlParams = [
            static::QUERY_PARAM_ID_SALES_ORDER => $idSalesOrder,
        ];

        return [
            OmsTriggerForm::OPTION_OMS_ACTION => static::OMS_ACTION_ORDER_TRIGGER,
            OmsTriggerForm::OPTION_EVENT => $event,
            OmsTriggerForm::OPTION_SUBMIT_BUTTON_CLASS => static::SUBMIT_BUTTON_CLASS,
            OmsTriggerForm::OPTION_QUERY_PARAMS => [
                static::QUERY_PARAM_EVENT => $event,
                static::QUERY_PARAM_ID_SALES_ORDER => $idSalesOrder,
                static::QUERY_PARAM_REDIRECT => $this->createRedirectLink(static::ROUTE_REDIRECT, $redirectUrlParams),
            ],
        ];
    }
}
