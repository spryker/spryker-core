<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDynamicEntityConnector\Business;

use Spryker\Zed\CategoryDynamicEntityConnector\Business\Creator\CategoryClosureTableCreator;
use Spryker\Zed\CategoryDynamicEntityConnector\Business\Creator\CategoryClosureTableCreatorInterface;
use Spryker\Zed\CategoryDynamicEntityConnector\Business\Creator\CategoryUrlCreator;
use Spryker\Zed\CategoryDynamicEntityConnector\Business\Creator\CategoryUrlCreatorInterface;
use Spryker\Zed\CategoryDynamicEntityConnector\Business\Publisher\CategoryTreePublisher;
use Spryker\Zed\CategoryDynamicEntityConnector\Business\Publisher\CategoryTreePublisherInterface;
use Spryker\Zed\CategoryDynamicEntityConnector\Business\Reader\CategoryReader;
use Spryker\Zed\CategoryDynamicEntityConnector\Business\Reader\CategoryReaderInterface;
use Spryker\Zed\CategoryDynamicEntityConnector\Business\Updater\CategoryClosureTableUpdater;
use Spryker\Zed\CategoryDynamicEntityConnector\Business\Updater\CategoryClosureTableUpdaterInterface;
use Spryker\Zed\CategoryDynamicEntityConnector\Business\Updater\CategoryUrlUpdater;
use Spryker\Zed\CategoryDynamicEntityConnector\Business\Updater\CategoryUrlUpdaterInterface;
use Spryker\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorDependencyProvider;
use Spryker\Zed\CategoryDynamicEntityConnector\Dependency\Facade\CategoryDynamicEntityConnectorToCategoryFacadeInterface;
use Spryker\Zed\CategoryDynamicEntityConnector\Dependency\Facade\CategoryDynamicEntityConnectorToEventFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorConfig getConfig()
 * @method \Spryker\Zed\CategoryDynamicEntityConnector\Persistence\CategoryDynamicEntityConnectorEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CategoryDynamicEntityConnector\Persistence\CategoryDynamicEntityConnectorRepositoryInterface getRepository()
 */
class CategoryDynamicEntityConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CategoryDynamicEntityConnector\Business\Creator\CategoryUrlCreatorInterface
     */
    public function createCategoryUrlCreator(): CategoryUrlCreatorInterface
    {
        return new CategoryUrlCreator(
            $this->getConfig(),
            $this->createCategoryReader(),
            $this->getCategoryFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryDynamicEntityConnector\Business\Updater\CategoryUrlUpdaterInterface
     */
    public function createCategoryUrlUpdater(): CategoryUrlUpdaterInterface
    {
        return new CategoryUrlUpdater(
            $this->getConfig(),
            $this->createCategoryReader(),
            $this->getCategoryFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryDynamicEntityConnector\Business\Creator\CategoryClosureTableCreatorInterface
     */
    public function createCategoryClosureTableCreator(): CategoryClosureTableCreatorInterface
    {
        return new CategoryClosureTableCreator(
            $this->getConfig(),
            $this->getCategoryFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryDynamicEntityConnector\Business\Updater\CategoryClosureTableUpdaterInterface
     */
    public function createCategoryClosureTableUpdater(): CategoryClosureTableUpdaterInterface
    {
        return new CategoryClosureTableUpdater(
            $this->getConfig(),
            $this->getCategoryFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryDynamicEntityConnector\Business\Publisher\CategoryTreePublisherInterface
     */
    public function createCategoryTreePublisher(): CategoryTreePublisherInterface
    {
        return new CategoryTreePublisher(
            $this->getConfig(),
            $this->getEventFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryDynamicEntityConnector\Business\Reader\CategoryReaderInterface
     */
    public function createCategoryReader(): CategoryReaderInterface
    {
        return new CategoryReader($this->getCategoryFacade());
    }

    /**
     * @return \Spryker\Zed\CategoryDynamicEntityConnector\Dependency\Facade\CategoryDynamicEntityConnectorToCategoryFacadeInterface
     */
    public function getCategoryFacade(): CategoryDynamicEntityConnectorToCategoryFacadeInterface
    {
        return $this->getProvidedDependency(CategoryDynamicEntityConnectorDependencyProvider::FACADE_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\CategoryDynamicEntityConnector\Dependency\Facade\CategoryDynamicEntityConnectorToEventFacadeInterface
     */
    public function getEventFacade(): CategoryDynamicEntityConnectorToEventFacadeInterface
    {
        return $this->getProvidedDependency(CategoryDynamicEntityConnectorDependencyProvider::FACADE_EVENT);
    }
}
