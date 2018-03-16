<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart\Plugin;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface;

/**
 * For Client PermissionDependencyProvider::getPermissionPlugins() registration
 */
class ReadCartPermissionPlugin extends AbstractPlugin implements ExecutablePermissionPluginInterface
{
    public const KEY = 'ReadCartPermissionPlugin';

    public const CONFIG_ID_QUOTE_COLLECTION = 'id_quote_collection';

    /**
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY;
    }

    /**
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

        return in_array($idQuote, $configuration[static::CONFIG_ID_QUOTE_COLLECTION]);
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
