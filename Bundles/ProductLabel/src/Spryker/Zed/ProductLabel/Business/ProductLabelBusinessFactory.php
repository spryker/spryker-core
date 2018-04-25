<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business;

use Psr\Log\LoggerInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductLabel\Business\Label\LabelCreator;
use Spryker\Zed\ProductLabel\Business\Label\LabelReader;
use Spryker\Zed\ProductLabel\Business\Label\LabelUpdater;
use Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionReader;
use Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionWriter;
use Spryker\Zed\ProductLabel\Business\Label\ValidityUpdater;
use Spryker\Zed\ProductLabel\Business\ProductAbstractRelation\ProductAbstractRelationDeleter;
use Spryker\Zed\ProductLabel\Business\ProductAbstractRelation\ProductAbstractRelationReader;
use Spryker\Zed\ProductLabel\Business\ProductAbstractRelation\ProductAbstractRelationUpdater;
use Spryker\Zed\ProductLabel\Business\ProductAbstractRelation\ProductAbstractRelationWriter;
use Spryker\Zed\ProductLabel\Business\Touch\LabelDictionaryTouchManager;
use Spryker\Zed\ProductLabel\Business\Touch\ProductAbstractRelationTouchManager;
use Spryker\Zed\ProductLabel\ProductLabelDependencyProvider;

/**
 * @method \Spryker\Zed\ProductLabel\ProductLabelConfig getConfig()
 * @method \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface getQueryContainer()
 */
class ProductLabelBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductLabel\Business\Label\LabelCreatorInterface
     */
    public function createLabelCreator()
    {
        return new LabelCreator(
            $this->createLocalizedAttributesCollectionWriter(),
            $this->getQueryContainer(),
            $this->createLabelDictionaryTouchManager()
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\Label\LabelUpdaterInterface
     */
    public function createLabelUpdater()
    {
        return new LabelUpdater(
            $this->createLocalizedAttributesCollectionWriter(),
            $this->getQueryContainer(),
            $this->createLabelDictionaryTouchManager(),
            $this->createProductAbstractRelationTouchManager()
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\Label\LabelReaderInterface
     */
    public function createLabelReader()
    {
        return new LabelReader(
            $this->getQueryContainer(),
            $this->createLocalizedAttributesCollectionReader()
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionWriterInterface
     */
    protected function createLocalizedAttributesCollectionWriter()
    {
        return new LocalizedAttributesCollectionWriter(
            $this->getQueryContainer(),
            $this->createLabelDictionaryTouchManager()
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\Touch\LabelDictionaryTouchManagerInterface
     */
    protected function createLabelDictionaryTouchManager()
    {
        return new LabelDictionaryTouchManager($this->getTouchFacade());
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(ProductLabelDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(ProductLabelDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionReaderInterface
     */
    public function createLocalizedAttributesCollectionReader()
    {
        return new LocalizedAttributesCollectionReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\ProductAbstractRelation\ProductAbstractRelationReaderInterface
     */
    public function createProductAbstractRelationReader()
    {
        return new ProductAbstractRelationReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\ProductAbstractRelation\ProductAbstractRelationWriterInterface
     */
    public function createProductAbstractRelationWriter()
    {
        return new ProductAbstractRelationWriter(
            $this->getQueryContainer(),
            $this->createProductAbstractRelationTouchManager()
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\ProductAbstractRelation\ProductAbstractRelationDeleterInterface
     */
    public function createProductAbstractRelationDeleter()
    {
        return new ProductAbstractRelationDeleter(
            $this->getQueryContainer(),
            $this->createProductAbstractRelationTouchManager()
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\Touch\ProductAbstractRelationTouchManagerInterface
     */
    protected function createProductAbstractRelationTouchManager()
    {
        return new ProductAbstractRelationTouchManager($this->getTouchFacade(), $this->getProductFacade());
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\Label\ValidityUpdaterInterface
     */
    public function createLabelValidityUpdater()
    {
        return new ValidityUpdater(
            $this->getQueryContainer(),
            $this->createLabelDictionaryTouchManager()
        );
    }

    /**
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return ProductAbstractRelation\ProductAbstractRelationUpdaterInterface
     */
    public function createProductAbstractRelationUpdater(?LoggerInterface $logger = null)
    {
        return new ProductAbstractRelationUpdater(
            $this->createProductAbstractRelationDeleter(),
            $this->createProductAbstractRelationWriter(),
            $this->getProductLabelRelationUpdaterPlugins(),
            $logger
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Dependency\Plugin\ProductLabelRelationUpdaterPluginInterface[]
     */
    protected function getProductLabelRelationUpdaterPlugins()
    {
        return $this->getProvidedDependency(ProductLabelDependencyProvider::PLUGIN_PRODUCT_LABEL_RELATION_UPDATERS);
    }
}
