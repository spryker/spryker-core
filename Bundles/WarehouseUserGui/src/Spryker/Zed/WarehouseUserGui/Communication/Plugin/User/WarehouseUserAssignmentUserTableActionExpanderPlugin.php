<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUserGui\Communication\Plugin\User;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserTableActionExpanderPluginInterface;

/**
 * @method \Spryker\Zed\WarehouseUserGui\Communication\WarehouseUserGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\WarehouseUserGui\WarehouseUserGuiConfig getConfig()
 */
class WarehouseUserAssignmentUserTableActionExpanderPlugin extends AbstractPlugin implements UserTableActionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands the table with the assign warehouses button.
     * - Returns an empty array if `spy_user.is_warehouse_user` is not `true` or 'spy_user.uuid' provided in user data is `null`.
     *
     * @api
     *
     * @param array<string, mixed> $user
     *
     * @return list<\Generated\Shared\Transfer\ButtonTransfer>
     */
    public function getActionButtonDefinitions(array $user): array
    {
        return $this->getFactory()
           ->createWarehouseUserAssignmentTableActionExpander()
           ->expand($user);
    }
}
