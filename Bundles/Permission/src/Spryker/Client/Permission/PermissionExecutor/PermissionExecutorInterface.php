<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Permission\PermissionExecutor;

interface PermissionExecutorInterface
{
    /**
     * @param string $permissionKey
     * @param array|string|int|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $context = null): bool;
}
