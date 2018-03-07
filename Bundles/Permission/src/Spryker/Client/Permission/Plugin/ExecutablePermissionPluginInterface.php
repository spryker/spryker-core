<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Permission\Plugin;

use Spryker\Shared\Permission\Dependency\Plugin\ExecutablePermissionPluginInterface as SharedExecutablePermissionPluginInterface;

/**
 * @method bool can(array $configuration, $context = null)
 * @method array getConfigurationSignature()
 */
interface ExecutablePermissionPluginInterface extends SharedExecutablePermissionPluginInterface
{
}
