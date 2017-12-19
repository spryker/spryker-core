<?php

namespace Spryker\Client\Rbac;

interface RbacClientInterface
{
    /**
     * @param string $right
     * @param array $options
     *
     * @return bool
     */
    public function can($right, array $options);
}