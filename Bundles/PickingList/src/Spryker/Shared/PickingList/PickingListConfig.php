<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PickingList;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class PickingListConfig extends AbstractSharedConfig
{
    /**
     * Specification:
     * - The picking list status when the list is ready for picking.
     *
     * @api
     *
     * @var string
     */
    public const STATUS_READY_FOR_PICKING = 'ready-for-picking';

    /**
     * Specification:
     * - The picking list status when picking has started.
     *
     * @api
     *
     * @var string
     */
    public const STATUS_PICKING_STARTED = 'picking-started';

    /**
     * Specification:
     * - The picking list status when picking has finished.
     *
     * @api
     *
     * @var string
     */
    public const STATUS_PICKING_FINISHED = 'picking-finished';

    /**
     * Specification:
     * - Returns the list of statuses when order picking list is started.
     *
     * @api
     *
     * @return list<string>
     */
    public function getOrderPickingListStartedStatuses(): array
    {
        return [
            static::STATUS_PICKING_STARTED,
            static::STATUS_PICKING_FINISHED,
        ];
    }
}
