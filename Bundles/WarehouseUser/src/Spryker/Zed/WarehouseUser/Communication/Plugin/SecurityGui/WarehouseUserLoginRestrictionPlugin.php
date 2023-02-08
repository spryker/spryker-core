<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Communication\Plugin\SecurityGui;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecurityGuiExtension\Dependency\Plugin\UserLoginRestrictionPluginInterface;

/**
 * @method \Spryker\Zed\WarehouseUser\WarehouseUserConfig getConfig()
 * @method \Spryker\Zed\WarehouseUser\Business\WarehouseUserFacadeInterface getFacade()
 */
class WarehouseUserLoginRestrictionPlugin extends AbstractPlugin implements UserLoginRestrictionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Restricts access for warehouse users.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return bool
     */
    public function isRestricted(UserTransfer $userTransfer): bool
    {
        return $userTransfer->getIsWarehouseUser() === true;
    }
}
