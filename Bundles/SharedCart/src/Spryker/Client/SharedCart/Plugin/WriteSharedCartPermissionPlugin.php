<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart\Plugin;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\InfrastructuralPermissionPluginInterface;
use Spryker\Shared\SharedCart\SharedCartConfig;

/**
 * For Client PermissionDependencyProvider::getPermissionPlugins() registration
 */
class WriteSharedCartPermissionPlugin extends AbstractPlugin implements ExecutablePermissionPluginInterface, InfrastructuralPermissionPluginInterface
{
    public const KEY = 'WriteSharedCartPermissionPlugin';

    /**
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY;
    }

    /**
     * Specification:
     * - Implements a business login against the configuration and the passed context
     *
     * @api
     *
     * @param array $configuration
     * @param int|null $idQuote
     *
     * @return bool
     */
    public function can(array $configuration, $idQuote = null)
    {
        if (!$idQuote) {
            return false;
        }

        return in_array($idQuote, $configuration[SharedCartConfig::PERMISSION_CONFIG_ID_QUOTE_COLLECTION]);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getConfigurationSignature()
    {
        return [];
    }
}
