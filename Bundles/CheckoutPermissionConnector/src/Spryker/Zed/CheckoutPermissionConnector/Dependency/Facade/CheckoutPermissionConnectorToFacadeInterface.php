<?php

namespace Spryker\Zed\CheckoutPermissionConnector\Dependency;


interface CheckoutPermissionConnectorToFacadeInterface
{
    /**
     * @param string $permissionKey
     * @param int|string $identifier
     * @param int|string|array|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $identifier, $context = null);
}