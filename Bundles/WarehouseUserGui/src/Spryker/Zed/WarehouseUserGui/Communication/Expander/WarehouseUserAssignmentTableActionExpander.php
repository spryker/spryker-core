<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUserGui\Communication\Expander;

use Generated\Shared\Transfer\ButtonTransfer;
use Spryker\Service\UtilText\Model\Url\Url;

class WarehouseUserAssignmentTableActionExpander implements WarehouseUserAssignmentTableActionExpanderInterface
{
    /**
     * @uses \Spryker\Zed\WarehouseUserGui\Communication\Controller\AssignWarehouseController::PARAM_USER_UUID
     *
     * @var string
     */
    protected const PARAM_USER_UUID = 'user-uuid';

    /**
     * @uses \Spryker\Zed\WarehouseUserGui\Communication\Controller\AssignWarehouseController::URL_ASSIGN_WAREHOUSE
     *
     * @var string
     */
    protected const URL_ASSIGN_WAREHOUSE = '/warehouse-user-gui/assign-warehouse';

    /**
     * @var string
     */
    protected const TITLE_ASSIGN_WAREHOUSES = 'Assign Warehouses';

    /**
     * @var string
     */
    protected const DEFAULT_OPTION_CLASS = 'btn-edit btn-view';

    /**
     * @var string
     */
    protected const DEFAULT_OPTION_ICON = 'fa-pencil-square-o';

    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_IS_WAREHOUSE_USER
     *
     * @var string
     */
    protected const COL_IS_WAREHOUSE_USER = 'spy_user.is_warehouse_user';

    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_UUID
     *
     * @var string
     */
    protected const COL_UUID = 'spy_user.uuid';

    /**
     * @param array<string, mixed> $user
     *
     * @return list<\Generated\Shared\Transfer\ButtonTransfer>
     */
    public function expand(array $user): array
    {
        if ($user[static::COL_IS_WAREHOUSE_USER] !== true || $user[static::COL_UUID] === null) {
            return [];
        }

        return [$this->createWarehouseUserAssignmentButton($user)];
    }

    /**
     * @param array<string, mixed> $user
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer
     */
    protected function createWarehouseUserAssignmentButton(array $user): ButtonTransfer
    {
        return (new ButtonTransfer())
            ->setUrl($this->createWarehouseUserAssignmentUrl($user[static::COL_UUID]))
            ->setTitle(static::TITLE_ASSIGN_WAREHOUSES)
            ->setDefaultOptions([
                'class' => static::DEFAULT_OPTION_CLASS,
                'icon' => static::DEFAULT_OPTION_ICON,
            ]);
    }

    /**
     * @param string $userUuid
     *
     * @return string
     */
    protected function createWarehouseUserAssignmentUrl(string $userUuid): string
    {
        return Url::generate(
            static::URL_ASSIGN_WAREHOUSE,
            [
                static::PARAM_USER_UUID => $userUuid,
            ],
        );
    }
}
