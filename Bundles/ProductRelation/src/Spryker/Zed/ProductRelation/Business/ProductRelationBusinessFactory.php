<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductRelation\Business\Builder\ProductRelationBuilder;
use Spryker\Zed\ProductRelation\Business\Builder\ProductRelationBuilderInterface;
use Spryker\Zed\ProductRelation\Business\Relation\Creator\ProductRelationCreator;
use Spryker\Zed\ProductRelation\Business\Relation\Creator\ProductRelationCreatorInterface;
use Spryker\Zed\ProductRelation\Business\Relation\Deleter\ProductRelationDeleter;
use Spryker\Zed\ProductRelation\Business\Relation\Deleter\ProductRelationDeleterInterface;
use Spryker\Zed\ProductRelation\Business\Relation\ProductRelationActivator;
use Spryker\Zed\ProductRelation\Business\Relation\Reader\ProductRelationReader;
use Spryker\Zed\ProductRelation\Business\Relation\Reader\RelatedProductReader;
use Spryker\Zed\ProductRelation\Business\Relation\Reader\RelatedProductReaderInterface;
use Spryker\Zed\ProductRelation\Business\Relation\Updater\ProductRelationStoreRelationUpdater;
use Spryker\Zed\ProductRelation\Business\Relation\Updater\ProductRelationStoreRelationUpdaterInterface;
use Spryker\Zed\ProductRelation\Business\Relation\Updater\ProductRelationUpdater;
use Spryker\Zed\ProductRelation\Business\Relation\Updater\ProductRelationUpdaterInterface;
use Spryker\Zed\ProductRelation\Business\Relation\Updater\RelatedProductUpdater;
use Spryker\Zed\ProductRelation\Business\Relation\Updater\RelatedProductUpdaterInterface;
use Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface;
use Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface;
use Spryker\Zed\ProductRelation\ProductRelationDependencyProvider;

/**
 * @method \Spryker\Zed\ProductRelation\ProductRelationConfig getConfig()
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationEntityManagerInterface getEntityManager()
 */
class ProductRelationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductRelation\Business\Relation\Reader\RelatedProductReaderInterface
     */
    public function createRelatedProductReader(): RelatedProductReaderInterface
    {
        return new RelatedProductReader(
            $this->getRepository(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Business\Relation\Updater\RelatedProductUpdaterInterface
     */
    public function createRelatedProductUpdater(): RelatedProductUpdaterInterface
    {
        return new RelatedProductUpdater(
            $this->createRelatedProductReader(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Business\Relation\Creator\ProductRelationCreatorInterface
     */
    public function createProductRelationCreator(): ProductRelationCreatorInterface
    {
        return new ProductRelationCreator(
            $this->getEntityManager(),
            $this->getTouchFacade(),
            $this->createRelatedProductUpdater(),
            $this->createProductRelationStoreRelationUpdater()
        );
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Business\Relation\Updater\ProductRelationUpdaterInterface
     */
    public function createProductRelationUpdater(): ProductRelationUpdaterInterface
    {
        return new ProductRelationUpdater(
            $this->getEntityManager(),
            $this->createRelatedProductUpdater(),
            $this->getTouchFacade(),
            $this->createProductRelationStoreRelationUpdater()
        );
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Business\Relation\Deleter\ProductRelationDeleterInterface
     */
    public function createProductRelationDeleter(): ProductRelationDeleterInterface
    {
        return new ProductRelationDeleter(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getTouchFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Business\Relation\Updater\ProductRelationStoreRelationUpdaterInterface
     */
    public function createProductRelationStoreRelationUpdater(): ProductRelationStoreRelationUpdaterInterface
    {
        return new ProductRelationStoreRelationUpdater(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Business\Relation\Reader\ProductRelationReaderInterface
     */
    public function createProductRelationReader()
    {
        return new ProductRelationReader(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Business\Relation\ProductRelationActivatorInterface
     */
    public function createProductRelationActivator()
    {
        return new ProductRelationActivator($this->getQueryContainer(), $this->getTouchFacade());
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Business\Builder\ProductRelationBuilderInterface
     */
    public function createProductRelationBuilder(): ProductRelationBuilderInterface
    {
        return new ProductRelationBuilder(
            $this->createProductRelationUpdater(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface
     */
    protected function getTouchFacade(): ProductRelationToTouchInterface
    {
        return $this->getProvidedDependency(ProductRelationDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface
     */
    protected function getUtilEncodingService(): ProductRelationToUtilEncodingInterface
    {
        return $this->getProvidedDependency(ProductRelationDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
