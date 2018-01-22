<?php

namespace Spryker\Zed\CategoryStorage\Business;

use Spryker\Zed\CategoryStorage\Business\Storage\CategoryNodeStorage;
use Spryker\Zed\CategoryStorage\Business\Storage\CategoryNodeStorageInterface;
use Spryker\Zed\CategoryStorage\Business\Storage\CategoryTreeStorage;
use Spryker\Zed\CategoryStorage\Business\Storage\CategoryTreeStorageInterface;
use Spryker\Zed\CategoryStorage\CategoryStorageDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CategoryStorage\CategoryStorageConfig getConfig()
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainer getQueryContainer()
 */
class CategoryStorageBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return CategoryNodeStorageInterface
     */
    public function createCategoryNodeStorage()
    {
        return new CategoryNodeStorage(
            $this->getQueryContainer(),
            $this->getUtilSanitizeService(),
            $this->getStore(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return CategoryTreeStorageInterface
     */
    public function createCategoryTreeStorage()
    {
        return new CategoryTreeStorage(
            $this->getQueryContainer(),
            $this->getUtilSanitizeService(),
            $this->getStore(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(CategoryStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(CategoryStorageDependencyProvider::STORE);
    }
}
