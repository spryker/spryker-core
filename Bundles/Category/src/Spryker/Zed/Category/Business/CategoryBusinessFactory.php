<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business;

use Spryker\Zed\Category\Business\Creator\CategoryAttributeCreator;
use Spryker\Zed\Category\Business\Creator\CategoryAttributeCreatorInterface;
use Spryker\Zed\Category\Business\Creator\CategoryClosureTableCreator;
use Spryker\Zed\Category\Business\Creator\CategoryClosureTableCreatorInterface;
use Spryker\Zed\Category\Business\Creator\CategoryCreator;
use Spryker\Zed\Category\Business\Creator\CategoryCreatorInterface;
use Spryker\Zed\Category\Business\Creator\CategoryNodeCreator;
use Spryker\Zed\Category\Business\Creator\CategoryNodeCreatorInterface;
use Spryker\Zed\Category\Business\Creator\CategoryRelationshipCreator;
use Spryker\Zed\Category\Business\Creator\CategoryRelationshipCreatorInterface;
use Spryker\Zed\Category\Business\Creator\CategoryStoreCreator;
use Spryker\Zed\Category\Business\Creator\CategoryStoreCreatorInterface;
use Spryker\Zed\Category\Business\Creator\CategoryUrlCreator;
use Spryker\Zed\Category\Business\Creator\CategoryUrlCreatorInterface;
use Spryker\Zed\Category\Business\Deleter\CategoryAttributeDeleter;
use Spryker\Zed\Category\Business\Deleter\CategoryAttributeDeleterInterface;
use Spryker\Zed\Category\Business\Deleter\CategoryClosureTableDeleter;
use Spryker\Zed\Category\Business\Deleter\CategoryClosureTableDeleterInterface;
use Spryker\Zed\Category\Business\Deleter\CategoryDeleter;
use Spryker\Zed\Category\Business\Deleter\CategoryDeleterInterface;
use Spryker\Zed\Category\Business\Deleter\CategoryNodeDeleter;
use Spryker\Zed\Category\Business\Deleter\CategoryNodeDeleterInterface;
use Spryker\Zed\Category\Business\Deleter\CategoryRelationshipDeleter;
use Spryker\Zed\Category\Business\Deleter\CategoryRelationshipDeleterInterface;
use Spryker\Zed\Category\Business\Deleter\CategoryStoreDeleter;
use Spryker\Zed\Category\Business\Deleter\CategoryStoreDeleterInterface;
use Spryker\Zed\Category\Business\Deleter\CategoryUrlDeleter;
use Spryker\Zed\Category\Business\Deleter\CategoryUrlDeleterInterface;
use Spryker\Zed\Category\Business\Generator\TransferGenerator;
use Spryker\Zed\Category\Business\Generator\TransferGeneratorInterface;
use Spryker\Zed\Category\Business\Generator\UrlPathGenerator;
use Spryker\Zed\Category\Business\Generator\UrlPathGeneratorInterface;
use Spryker\Zed\Category\Business\Model\Category\CategoryHydrator;
use Spryker\Zed\Category\Business\Model\Category\CategoryHydratorInterface;
use Spryker\Zed\Category\Business\Model\CategoryTemplate\CategoryTemplateSync;
use Spryker\Zed\Category\Business\Model\CategoryTemplate\CategoryTemplateSyncInterface;
use Spryker\Zed\Category\Business\Model\CategoryToucher;
use Spryker\Zed\Category\Business\Model\CategoryToucherInterface;
use Spryker\Zed\Category\Business\Model\CategoryTree\CategoryTree;
use Spryker\Zed\Category\Business\Model\CategoryTree\CategoryTreeInterface;
use Spryker\Zed\Category\Business\Publisher\CategoryNodePublisher;
use Spryker\Zed\Category\Business\Publisher\CategoryNodePublisherInterface;
use Spryker\Zed\Category\Business\Reader\CategoryReader;
use Spryker\Zed\Category\Business\Reader\CategoryReaderInterface;
use Spryker\Zed\Category\Business\Tree\CategoryTreeReader;
use Spryker\Zed\Category\Business\Updater\CategoryAttributeUpdater;
use Spryker\Zed\Category\Business\Updater\CategoryAttributeUpdaterInterface;
use Spryker\Zed\Category\Business\Updater\CategoryClosureTableUpdater;
use Spryker\Zed\Category\Business\Updater\CategoryClosureTableUpdaterInterface;
use Spryker\Zed\Category\Business\Updater\CategoryNodeUpdater;
use Spryker\Zed\Category\Business\Updater\CategoryNodeUpdaterInterface;
use Spryker\Zed\Category\Business\Updater\CategoryRelationshipUpdater;
use Spryker\Zed\Category\Business\Updater\CategoryRelationshipUpdaterInterface;
use Spryker\Zed\Category\Business\Updater\CategoryStoreUpdater;
use Spryker\Zed\Category\Business\Updater\CategoryStoreUpdaterInterface;
use Spryker\Zed\Category\Business\Updater\CategoryUpdater;
use Spryker\Zed\Category\Business\Updater\CategoryUpdaterInterface;
use Spryker\Zed\Category\Business\Updater\CategoryUrlUpdater;
use Spryker\Zed\Category\Business\Updater\CategoryUrlUpdaterInterface;
use Spryker\Zed\Category\CategoryDependencyProvider;
use Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface;
use Spryker\Zed\Category\Dependency\Facade\CategoryToTouchInterface;
use Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface;
use Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryStoreAssignerPluginInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface getRepository()
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Category\CategoryConfig getConfig()
 */
class CategoryBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Category\Business\Reader\CategoryReaderInterface
     */
    public function createCategoryReader(): CategoryReaderInterface
    {
        return new CategoryReader(
            $this->getRepository(),
            $this->createCategoryHydrator(),
            $this->createCategoryTreeReader(),
            $this->getCategoryTransferExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Creator\CategoryCreatorInterface
     */
    public function createCategoryCreator(): CategoryCreatorInterface
    {
        return new CategoryCreator(
            $this->getEntityManager(),
            $this->createCategoryRelationshipCreator(),
            $this->getEventFacade(),
            $this->getCategoryCreateAfterPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Creator\CategoryNodeCreatorInterface
     */
    public function createCategoryNodeCreator(): CategoryNodeCreatorInterface
    {
        return new CategoryNodeCreator(
            $this->getEntityManager(),
            $this->createCategoryNodePublisher(),
            $this->createCategoryClosureTableCreator(),
            $this->createCategoryUrlCreator(),
            $this->createCategoryToucher()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Creator\CategoryUrlCreatorInterface
     */
    public function createCategoryUrlCreator(): CategoryUrlCreatorInterface
    {
        return new CategoryUrlCreator(
            $this->getUrlFacade(),
            $this->getRepository(),
            $this->createUrlPathGenerator(),
            $this->getCategoryUrlPathPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Creator\CategoryAttributeCreatorInterface
     */
    public function createCategoryAttributeCreator(): CategoryAttributeCreatorInterface
    {
        return new CategoryAttributeCreator($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\Category\Business\Creator\CategoryClosureTableCreatorInterface
     */
    public function createCategoryClosureTableCreator(): CategoryClosureTableCreatorInterface
    {
        return new CategoryClosureTableCreator($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\Category\Business\Updater\CategoryUpdaterInterface
     */
    public function createCategoryUpdater(): CategoryUpdaterInterface
    {
        return new CategoryUpdater(
            $this->getEntityManager(),
            $this->createCategoryRelationshipUpdater(),
            $this->getEventFacade(),
            $this->getCategoryUpdateAfterPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Updater\CategoryRelationshipUpdaterInterface
     */
    public function createCategoryRelationshipUpdater(): CategoryRelationshipUpdaterInterface
    {
        return new CategoryRelationshipUpdater(
            $this->createCategoryNodeUpdater(),
            $this->createCategoryUrlUpdater(),
            $this->createCategoryAttributeUpdater(),
            $this->createCategoryTemplateSync(),
            $this->getCategoryStoreAssignerPlugin(),
            $this->getCategoryRelationUpdatePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Updater\CategoryStoreUpdaterInterface
     */
    public function createCategoryStoreUpdater(): CategoryStoreUpdaterInterface
    {
        return new CategoryStoreUpdater(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createCategoryReader(),
            $this->getEventFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Updater\CategoryNodeUpdaterInterface
     */
    public function createCategoryNodeUpdater(): CategoryNodeUpdaterInterface
    {
        return new CategoryNodeUpdater(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createCategoryClosureTableUpdater(),
            $this->createCategoryToucher(),
            $this->createCategoryNodePublisher(),
            $this->createCategoryNodeDeleter(),
            $this->createCategoryNodeCreator()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Updater\CategoryClosureTableUpdaterInterface
     */
    public function createCategoryClosureTableUpdater(): CategoryClosureTableUpdaterInterface
    {
        return new CategoryClosureTableUpdater($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\Category\Business\Updater\CategoryAttributeUpdaterInterface
     */
    public function createCategoryAttributeUpdater(): CategoryAttributeUpdaterInterface
    {
        return new CategoryAttributeUpdater($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\Category\Business\Updater\CategoryUrlUpdaterInterface
     */
    public function createCategoryUrlUpdater(): CategoryUrlUpdaterInterface
    {
        return new CategoryUrlUpdater(
            $this->getRepository(),
            $this->createUrlPathGenerator(),
            $this->getUrlFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Tree\CategoryTreeReader
     */
    public function createCategoryTreeReader(): CategoryTreeReader
    {
        return new CategoryTreeReader(
            $this->getQueryContainer(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Model\CategoryToucherInterface
     */
    public function createCategoryToucher(): CategoryToucherInterface
    {
        return new CategoryToucher($this->getTouchFacade(), $this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Category\Business\Model\CategoryTree\CategoryTreeInterface
     */
    public function createCategoryTree(): CategoryTreeInterface
    {
        return new CategoryTree(
            $this->getQueryContainer(),
            $this->getEntityManager(),
            $this->createFacade(),
            $this->createCategoryNodePublisher(),
            $this->createCategoryToucher()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\CategoryFacadeInterface
     */
    public function createFacade(): CategoryFacadeInterface
    {
        return new CategoryFacade();
    }

    /**
     * @return \Spryker\Zed\Category\Business\Publisher\CategoryNodePublisherInterface
     */
    public function createCategoryNodePublisher(): CategoryNodePublisherInterface
    {
        return new CategoryNodePublisher(
            $this->getRepository(),
            $this->getEventFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Generator\UrlPathGeneratorInterface
     */
    public function createUrlPathGenerator(): UrlPathGeneratorInterface
    {
        return new UrlPathGenerator(
            $this->getRepository(),
            $this->getCategoryUrlPathPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Generator\TransferGeneratorInterface
     */
    public function createCategoryTransferGenerator(): TransferGeneratorInterface
    {
        return new TransferGenerator();
    }

    /**
     * @return \Spryker\Zed\Category\Business\Model\CategoryTemplate\CategoryTemplateSyncInterface
     */
    public function createCategoryTemplateSync(): CategoryTemplateSyncInterface
    {
        return new CategoryTemplateSync(
            $this->getQueryContainer(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Model\Category\CategoryHydratorInterface
     */
    public function createCategoryHydrator(): CategoryHydratorInterface
    {
        return new CategoryHydrator($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\Category\Business\Deleter\CategoryDeleterInterface
     */
    public function createCategoryDeleter(): CategoryDeleterInterface
    {
        return new CategoryDeleter(
            $this->getEntityManager(),
            $this->createCategoryRelationshipDeleter(),
            $this->getEventFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Deleter\CategoryAttributeDeleterInterface
     */
    public function createCategoryAttributeDeleter(): CategoryAttributeDeleterInterface
    {
        return new CategoryAttributeDeleter($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\Category\Business\Deleter\CategoryClosureTableDeleterInterface
     */
    public function createClosureTableDeleter(): CategoryClosureTableDeleterInterface
    {
        return new CategoryClosureTableDeleter($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\Category\Business\Deleter\CategoryNodeDeleterInterface
     */
    public function createCategoryNodeDeleter(): CategoryNodeDeleterInterface
    {
        return new CategoryNodeDeleter(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createCategoryTree(),
            $this->createClosureTableDeleter(),
            $this->createCategoryUrlDeleter(),
            $this->createCategoryToucher(),
            $this->createCategoryNodePublisher()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Deleter\CategoryUrlDeleterInterface
     */
    public function createCategoryUrlDeleter(): CategoryUrlDeleterInterface
    {
        return new CategoryUrlDeleter(
            $this->getRepository(),
            $this->getUrlFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Creator\CategoryStoreCreatorInterface
     */
    public function createCategoryStoreCreator(): CategoryStoreCreatorInterface
    {
        return new CategoryStoreCreator(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Creator\CategoryRelationshipCreatorInterface
     */
    public function createCategoryRelationshipCreator(): CategoryRelationshipCreatorInterface
    {
        return new CategoryRelationshipCreator(
            $this->createCategoryNodeCreator(),
            $this->createCategoryAttributeCreator(),
            $this->createCategoryUrlCreator(),
            $this->createCategoryStoreCreator(),
            $this->createCategoryTemplateSync(),
            $this->getCategoryRelationUpdatePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Deleter\CategoryRelationshipDeleterInterface
     */
    public function createCategoryRelationshipDeleter(): CategoryRelationshipDeleterInterface
    {
        return new CategoryRelationshipDeleter(
            $this->createCategoryAttributeDeleter(),
            $this->createCategoryUrlDeleter(),
            $this->createCategoryNodeDeleter(),
            $this->createCategoryStoreDeleter(),
            $this->getCategoryRelationDeletePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Deleter\CategoryStoreDeleterInterface
     */
    public function createCategoryStoreDeleter(): CategoryStoreDeleterInterface
    {
        return new CategoryStoreDeleter(
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface
     */
    public function getEventFacade(): CategoryToEventFacadeInterface
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::FACADE_EVENT);
    }

    /**
     * @return \Spryker\Zed\Category\Dependency\Facade\CategoryToTouchInterface
     */
    public function getTouchFacade(): CategoryToTouchInterface
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface
     */
    public function getUrlFacade(): CategoryToUrlInterface
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::FACADE_URL);
    }

    /**
     * @return \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryRelationDeletePluginInterface[]
     */
    public function getCategoryRelationDeletePlugins(): array
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::PLUGIN_STACK_RELATION_DELETE);
    }

    /**
     * @return \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryRelationUpdatePluginInterface[]
     */
    public function getCategoryRelationUpdatePlugins(): array
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::PLUGIN_STACK_RELATION_UPDATE);
    }

    /**
     * @return \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryUrlPathPluginInterface[]
     */
    public function getCategoryUrlPathPlugins(): array
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::PLUGINS_CATEGORY_URL_PATH);
    }

    /**
     * @return \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryCreateAfterPluginInterface[]
     */
    public function getCategoryCreateAfterPlugins(): array
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::PLUGIN_CATEGORY_POST_CREATE);
    }

    /**
     * @return \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryUpdateAfterPluginInterface[]
     */
    public function getCategoryUpdateAfterPlugins(): array
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::PLUGIN_CATEGORY_POST_UPDATE);
    }

    /**
     * @return \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryTransferExpanderPluginInterface[]
     */
    public function getCategoryTransferExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::PLUGIN_CATEGORY_POST_READ);
    }

    /**
     * @return \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryStoreAssignerPluginInterface
     */
    public function getCategoryStoreAssignerPlugin(): CategoryStoreAssignerPluginInterface
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::PLUGIN_CATEGORY_STORE_ASSIGNER);
    }
}
