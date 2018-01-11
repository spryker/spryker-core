<?php


namespace Spryker\Zed\Permission\Communication\Plugin;


interface ExecutablePermissionPluginInterface extends PermissionPluginInterface
{
    const CONFIG_FIELD_TYPE_INT = 'CONFIG_FIELD_TYPE_INT';
    const CONFIG_FIELD_TYPE_STRING = 'CONFIG_FIELD_TYPE_INT';

    /**
     * Specification:
     * - Implements a business login against the configuration and the passed context
     *
     * @param array $configuration
     * @param mixed $context
     *
     * @return bool
     */
    public function can(array $configuration, $context);

    /**
     * The signature is used to draw a form for filling on assigning a permission to a role.
     * Used a configuration array generation as well.
     *
     * Specification:
     * - Provides a signature for collection the plugin configuration
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