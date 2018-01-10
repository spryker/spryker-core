<?php


namespace Spryker\Client\Permission;


use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Permission\Dependency\Client\PermissionToCustomerClientInterface;
use Spryker\Client\Permission\PermissionFinder\PermissionFinder;
use Spryker\Client\Permission\PermissionFinder\PermissionFinderInterface;
use Spryker\Client\Permission\PermissionExecutor\PermissionExecutor;
use Spryker\Client\Permission\PermissionExecutor\PermissionExecutorInterface;
use Spryker\Client\Permission\Plugin\PermissionPluginInterface;
use Spryker\Client\Permission\Zed\PermissionStub;
use Spryker\Client\Permission\Zed\PermissionStubInterface;

class PermissionFactory extends AbstractFactory
{
    /**
     * @return PermissionToCustomerClientInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(PermissionDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return PermissionFinderInterface
     */
    public function createPermissionConfigurator()
    {
        return new PermissionFinder(
            $this->getPermissionPlugins()
        );
    }

    /**
     * @return PermissionExecutorInterface
     */
    public function createPermissionExecutor()
    {
        return new PermissionExecutor(
            $this->getCustomerClient(),
            $this->createPermissionConfigurator()
        );
    }

    /**
     * @return PermissionPluginInterface[]
     */
    protected function getPermissionPlugins()
    {
        return $this->getProvidedDependency(PermissionDependencyProvider::PLUGINS_PERMISSION);
    }
}