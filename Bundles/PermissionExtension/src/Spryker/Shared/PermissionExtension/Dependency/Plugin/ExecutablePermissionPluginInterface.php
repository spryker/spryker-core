<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PermissionExtension\Dependency\Plugin;

interface ExecutablePermissionPluginInterface extends PermissionPluginInterface
{
    public const CONFIG_FIELD_TYPE_INT = 'CONFIG_FIELD_TYPE_INT';
    public const CONFIG_FIELD_TYPE_STRING = 'CONFIG_FIELD_TYPE_STRING';

    /**
     * Specification:
     * - Implements a business login against the configuration and the passed context
     *
     * @api
     *
     * @param array $configuration
     * @param int|string|array|null $context
     *
     * @return bool
     */
    public function can(array $configuration, $context = null);

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
    public function getConfigurationSignature();
}
