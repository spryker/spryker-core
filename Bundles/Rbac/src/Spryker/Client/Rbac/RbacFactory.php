<?php


namespace Spryker\Client\Rbac;


use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Rbac\Zed\RbacStab;
use Spryker\Client\Rbac\Zed\RbacStabInterface;

class RbacFactory extends AbstractFactory
{
    /**
     * @return RbacStabInterface
     */
    public function createZedStub()
    {
        return new RbacStab();
    }
}