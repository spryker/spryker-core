<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\PickingList\PickingListConfig getSharedConfig()
 */
class PickingListConfig extends AbstractBundleConfig
{
    /**
     * @uses {@link \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_DELETED}
     * @uses {@link \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_BLOCKED}
     *
     * @var list<string>
     */
    protected const USER_STATUSES_APPLICABLE_FOR_PICKING_LIST_UNASSIGNMENT = [
        'deleted',
        'blocked',
    ];

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
        return $this->getSharedConfig()->getOrderPickingListStartedStatuses();
    }

    /**
     * Specification:
     * - Defines the list of user statuses that lead to picking lists unassignment from the user.
     *
     * @api
     *
     * @return list<string>
     */
    public function getUserStatusesApplicableForPickingListUnassignment(): array
    {
        return static::USER_STATUSES_APPLICABLE_FOR_PICKING_LIST_UNASSIGNMENT;
    }
}
