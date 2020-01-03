<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Form\DataProvider;

abstract class AbstractOmsTriggerFormDataProvider
{
    public const OMS_ACTION_ITEM_TRIGGER = 'submit-trigger-event-for-order-items';
    public const OMS_ACTION_ORDER_TRIGGER = 'submit-trigger-event-for-order';

    public const QUERY_PARAM_EVENT = 'event';
    public const QUERY_PARAM_ID_SALES_ORDER = 'id-sales-order';
    public const QUERY_PARAM_ID_SALES_ORDER_ITEM = 'id-sales-order-item';
    public const QUERY_PARAM_REDIRECT = 'redirect';

    public const SUBMIT_BUTTON_CLASS = 'btn btn-primary btn-sm trigger-event';

    /**
     * @param string $redirectUrl
     * @param string $event
     * @param int $idSalesOrderItem
     *
     * @return array
     */
    abstract public function getOptions(string $redirectUrl, string $event, int $idSalesOrderItem): array;
}
