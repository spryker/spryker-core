<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Permission\Stub;

use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;

class TestPermissionPlugin implements PermissionPluginInterface
{
    public const KEY = 'TestPermissionPlugin';

    /**
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY;
    }
}
