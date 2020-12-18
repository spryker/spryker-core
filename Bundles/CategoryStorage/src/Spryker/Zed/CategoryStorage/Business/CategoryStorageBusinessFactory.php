<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business;

use Spryker\Zed\CategoryStorage\Business\Mapper\CategoryNodeStorageMapper;
use Spryker\Zed\CategoryStorage\Business\Mapper\CategoryNodeStorageMapperInterface;
use Spryker\Zed\CategoryStorage\Business\Storage\CategoryNodeStorage;
use Spryker\Zed\CategoryStorage\Business\Storage\CategoryNodeStorageInterface;
use Spryker\Zed\CategoryStorage\Business\Storage\CategoryTreeStorage;
use Spryker\Zed\CategoryStorage\Business\Storage\CategoryTreeStorageInterface;
use Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilder;
use Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface;
use Spryker\Zed\CategoryStorage\CategoryStorageDependencyProvider;
use Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToCategoryFacadeInterface;
use Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToStoreFacadeInterface;
use Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CategoryStorage\CategoryStorageConfig getConfig()
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface getQueryContainer()
 */
class CategoryStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CategoryStorage\Business\Storage\CategoryNodeStorageInterface
     */
    public function createCategoryNodeStorage(): CategoryNodeStorageInterface
    {
        return new CategoryNodeStorage(
            $this->getQueryContainer(),
            $this->getRepository(),
            $this->createCategoryStorageNodeTreeBuilder(),
            $this->getUtilSanitizeService()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Business\Storage\CategoryTreeStorageInterface
     */
    public function createCategoryTreeStorage(): CategoryTreeStorageInterface
    {
        return new CategoryTreeStorage(
            $this->getQueryContainer(),
            $this->getRepository(),
            $this->createCategoryStorageNodeTreeBuilder(),
            $this->getUtilSanitizeService()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface
     */
    public function createCategoryStorageNodeTreeBuilder(): CategoryStorageNodeTreeBuilderInterface
    {
        return new CategoryStorageNodeTreeBuilder(
            $this->getStoreFacade(),
            $this->createCategoryNodeStorageMapper()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Business\Mapper\CategoryNodeStorageMapperInterface
     */
    public function createCategoryNodeStorageMapper(): CategoryNodeStorageMapperInterface
    {
        return new CategoryNodeStorageMapper();
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToCategoryFacadeInterface
     */
    public function getCategoryFacade(): CategoryStorageToCategoryFacadeInterface
    {
        return $this->getProvidedDependency(CategoryStorageDependencyProvider::FACADE_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToStoreFacadeInterface
     */
    public function getStoreFacade(): CategoryStorageToStoreFacadeInterface
    {
        return $this->getProvidedDependency(CategoryStorageDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService(): CategoryStorageToUtilSanitizeServiceInterface
    {
        return $this->getProvidedDependency(CategoryStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }
}
