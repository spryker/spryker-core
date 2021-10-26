<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel;

use Spryker\Zed\Permission\Business\PermissionFacadeInterface;

trait PermissionAwareTrait
{
    /**
     * @uses \Spryker\Zed\Permission\Business\PermissionFacadeInterface
     *
     * @param string $permissionKey
     * @param string|int $identifier
     * @param array|string|int|null $context
     *
     * @return bool
     */
    protected function can($permissionKey, $identifier, $context = null)
    {
        if (interface_exists(PermissionFacadeInterface::class)) {
            return Locator::getInstance()->permission()->facade()->can($permissionKey, $identifier, $context);
        }

        return true;
    }
}
