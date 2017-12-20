<?php

namespace Spryker\Client\Rbac\Plugin;


interface RbacRightExecutionAwareInterface
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