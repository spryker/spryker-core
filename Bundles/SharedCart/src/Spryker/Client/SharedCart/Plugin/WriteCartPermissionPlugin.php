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
class WriteCartPermissionPlugin extends AbstractPlugin implements ExecutablePermissionPluginInterface
{
    public const KEY = 'WriteCartPermissionPlugin';

    public const CONFIG_ID_QUOTE_COLLECTION = 'id_quote_collection';

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

        return in_array($idQuote, $configuration[static::CONFIG_ID_QUOTE_COLLECTION]);
    }

    /**
     * The signature is used to draw a form for filling on assigning a permission to a role.
     * Used a configuration array generation as well.
     *
     * Specification:
     * - Provides a signature for collection the plugin configuration
     *
     * @api
     *
     * @example
     * [
     *      'amount' => ExecutablePermissionPluginInterface::CONFIG_FIELD_TYPE_INT,
     *      'item_count' => ExecutablePermissionPluginInterface::CONFIG_FIELD_TYPE_INT
     * ]
     *
     * @return array
     */
    public function getConfigurationSignature()
    {
        return [];
    }
}
