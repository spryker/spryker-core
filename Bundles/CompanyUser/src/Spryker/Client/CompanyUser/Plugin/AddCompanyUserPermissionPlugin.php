<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUser\Plugin;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;

/**
 * For Client PermissionDependencyProvider::getPermissionPlugins() registration
 */
class AddCompanyUserPermissionPlugin extends AbstractPlugin implements PermissionPluginInterface
{
    public const KEY = 'AddCompanyUserPermissionPlugin';

    /**
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY;
    }
}
