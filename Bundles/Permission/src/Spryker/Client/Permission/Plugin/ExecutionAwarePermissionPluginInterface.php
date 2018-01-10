<?php


namespace Spryker\Client\Permission\Plugin;


interface ExecutionAwarePermissionPluginInterface extends PermissionPluginInterface
{
    /**
     * @param array $options
     *
     * @return bool
     */
    public function can(array $options);

    /**
     * @param array $config
     *
     * @return void
     */
    public function configure(array $config);
}