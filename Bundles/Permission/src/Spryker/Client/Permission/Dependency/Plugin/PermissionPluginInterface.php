<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Permission\Dependency\Plugin;

use \Spryker\Shared\Permission\Dependency\Plugin\PermissionPluginInterface as SharedPermissionPluginInterface;

/**
 * @method string getKey(): string
 */
interface PermissionPluginInterface extends SharedPermissionPluginInterface
{
}
