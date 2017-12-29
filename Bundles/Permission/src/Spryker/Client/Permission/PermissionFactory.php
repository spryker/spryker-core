<?php


namespace Spryker\Client\Permission;


use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Permission\Zed\PermissionStub;
use Spryker\Client\Permission\Zed\PermissionStubInterface;

class PermissionFactory extends AbstractFactory
{
    /**
     * @return PermissionStubInterface
     */
    public function createZedStub()
    {
        return new PermissionStub();
    }
}