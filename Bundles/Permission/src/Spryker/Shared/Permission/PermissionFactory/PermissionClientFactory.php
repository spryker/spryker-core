<?php

namespace Spryker\Shared\Permission\PermissionFactory;

use Spryker\Client\Kernel\Locator;
use Spryker\Shared\Kernel\Permission\PermissionFactoryInterface;
use Spryker\Shared\Kernel\Permission\PermissionInterface;

class PermissionClientFactory implements PermissionFactoryInterface
{
    private $permissionClient;

    /**
     * @return PermissionInterface
     */
    public function createZedPermission()
    {
        return $this->getPermission();
    }

    /**
     * @return PermissionInterface
     */
    public function createYvesPermission()
    {
        return $this->getPermission();
    }

    /**
     * @return PermissionInterface
     */
    protected function getPermission()
    {
        if (!$this->permissionClient) {
            $this->permissionClient = Locator::getInstance()->permission()->client();
        }

        return $this->permissionClient;
    }
}