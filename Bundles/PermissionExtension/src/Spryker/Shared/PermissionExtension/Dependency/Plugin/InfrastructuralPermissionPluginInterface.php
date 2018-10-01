<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PermissionExtension\Dependency\Plugin;

/**
 * Specification:
 * - This plugin interface defines that the permission is infrastructural one, and isn't intended for manual configuration.
 * - All permission plugins, that implements this plugin interface will be excluded from available permissions of company roles.
 */
interface InfrastructuralPermissionPluginInterface
{
}
