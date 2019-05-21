<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Form\OmsTriggerForm;

class OmsTriggerFormDataProvider
{
    public const OMS_ACTION_ITEM_TRIGGER = 'trigger-event-for-order-items';
    public const SUBMIT_BUTTON_CLASS = 'btn btn-primary btn-sm trigger-order-single-event';
    public const QUERY_PARAM_EVENT = 'event';
    public const QUERY_PARAM_ID_SALES_ORDER = 'id-sales-order';
    public const QUERY_PARAM_ID_SALES_ORDER_ITEM = 'id-sales-order-item';
    public const QUERY_PARAM_REDIRECT = 'redirect';

    public const ROUTE_ORDER_ITEM_REDIRECT = '/sales/detail';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $event
     *
     * @return array
     */
    public function getOrderItemOmsTriggerFormOptions(ItemTransfer $itemTransfer, string $event): array
    {
        return [
            OmsTriggerForm::OPTION_OMS_ACTION => static::OMS_ACTION_ITEM_TRIGGER,
            OmsTriggerForm::OPTION_EVENT => $event,
            OmsTriggerForm::OPTION_SUBMIT_BUTTON_CLASS => static::SUBMIT_BUTTON_CLASS,
            OmsTriggerForm::OPTION_QUERY_PARAMS => [
                static::QUERY_PARAM_EVENT => $event,
                static::QUERY_PARAM_ID_SALES_ORDER_ITEM => $itemTransfer->getIdSalesOrderItem(),
                static::QUERY_PARAM_REDIRECT => $this->createOrderItemRedirectLink($itemTransfer),
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function createOrderItemRedirectLink(ItemTransfer $itemTransfer): string
    {
        return Url::generate(
            static::ROUTE_ORDER_ITEM_REDIRECT,
            [static::QUERY_PARAM_ID_SALES_ORDER => $itemTransfer->getFkSalesOrder()]
        );
    }
}
