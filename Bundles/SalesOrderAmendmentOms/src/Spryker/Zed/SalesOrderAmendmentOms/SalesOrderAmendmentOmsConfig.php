<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesOrderAmendmentOmsConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const EVENT_START_ORDER_AMENDMENT = 'start-order-amendment';

    /**
     * @var string
     */
    protected const EVENT_FINISH_ORDER_AMENDMENT = 'finish-order-amendment';

    /**
     * @var string
     */
    protected const EVENT_CANCEL_ORDER_AMENDMENT = 'cancel-order-amendment';

    /**
     * @var string
     */
    protected const OMS_FLAG_AMENDABLE = 'amendable';

    /**
     * @var string
     */
    protected const OMS_FLAG_AMENDMENT_IN_PROGRESS = 'amendment in progress';

    /**
     * Specification:
     * - It should return the name of the state the order was in before the amendment started: the grace period started.
     *
     * @api
     *
     * @return string
     */
    public function getOrderAmendmentOrderItemInitialState(): string
    {
        return 'new';
    }

    /**
     * Specification:
     * - Returns a start order amendment event name.
     *
     * @api
     *
     * @return string
     */
    public function getStartOrderAmendmentEvent(): string
    {
        return static::EVENT_START_ORDER_AMENDMENT;
    }

    /**
     * Specification:
     * - Returns a finish order amendment event name.
     *
     * @api
     *
     * @return string
     */
    public function getFinishOrderAmendmentEvent(): string
    {
        return static::EVENT_FINISH_ORDER_AMENDMENT;
    }

    /**
     * Specification:
     * - Returns a cancel order amendment event name.
     *
     * @api
     *
     * @return string
     */
    public function getCancelOrderAmendmentEvent(): string
    {
        return static::EVENT_CANCEL_ORDER_AMENDMENT;
    }

    /**
     * Specification:
     * - Returns amendable OMS flag name.
     *
     * @api
     *
     * @return string
     */
    public function getAmendableOmsFlag(): string
    {
        return static::OMS_FLAG_AMENDABLE;
    }

    /**
     * Specification:
     * - Returns a flag name for state when order is being amended.
     *
     * @api
     *
     * @return string
     */
    public function getAmendmentInProgressOmsFlag(): string
    {
        return static::OMS_FLAG_AMENDMENT_IN_PROGRESS;
    }
}
