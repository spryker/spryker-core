<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Form\DataProvider;

abstract class AbstractOmsTriggerFormDataProvider
{
    /**
     * @var string
     */
    public const OMS_ACTION_ITEM_TRIGGER = 'submit-trigger-event-for-order-items';
    /**
     * @var string
     */
    public const OMS_ACTION_ORDER_TRIGGER = 'submit-trigger-event-for-order';

    /**
     * @var string
     */
    public const QUERY_PARAM_EVENT = 'event';
    /**
     * @var string
     */
    public const QUERY_PARAM_ID_SALES_ORDER = 'id-sales-order';
    /**
     * @var string
     */
    public const QUERY_PARAM_ID_SALES_ORDER_ITEM = 'id-sales-order-item';
    /**
     * @var string
     */
    public const QUERY_PARAM_REDIRECT = 'redirect';

    /**
     * @var string
     */
    public const SUBMIT_BUTTON_CLASS = 'btn btn-primary btn-sm trigger-event';

    /**
     * @param string $redirectUrl
     * @param string $event
     * @param int $id
     *
     * @return array
     */
    abstract public function getOptions(string $redirectUrl, string $event, int $id): array;
}
