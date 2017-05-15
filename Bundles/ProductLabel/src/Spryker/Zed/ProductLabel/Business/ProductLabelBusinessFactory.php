<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductLabel\Business\AbstractProductRelation\AbstractProductRelationDeleter;
use Spryker\Zed\ProductLabel\Business\AbstractProductRelation\AbstractProductRelationReader;
use Spryker\Zed\ProductLabel\Business\AbstractProductRelation\AbstractProductRelationWriter;
use Spryker\Zed\ProductLabel\Business\Label\LabelCreator;
use Spryker\Zed\ProductLabel\Business\Label\LabelReader;
use Spryker\Zed\ProductLabel\Business\Label\LabelUpdater;
use Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionReader;
use Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionWriter;
use Spryker\Zed\ProductLabel\Business\Touch\AbstractProductRelationTouchManager;
use Spryker\Zed\ProductLabel\Business\Touch\LabelDictionaryTouchManager;
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
            $this->getQueryContainer(),
            $this->createLabelDictionaryTouchManager()
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
        return new LocalizedAttributesCollectionWriter();
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
     * @return \Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionReaderInterface
     */
    public function createLocalizedAttributesCollectionReader()
    {
        return new LocalizedAttributesCollectionReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\AbstractProductRelation\AbstractProductRelationReaderInterface
     */
    public function createAbstractProductRelationReader()
    {
        return new AbstractProductRelationReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\AbstractProductRelation\AbstractProductRelationWriterInterface
     */
    public function createAbstractProductRelationWriter()
    {
        return new AbstractProductRelationWriter(
            $this->getQueryContainer(),
            $this->createAbstractProductRelationDeleter(),
            $this->createAbstractProductRelationTouchManager()
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\AbstractProductRelation\AbstractProductRelationDeleterInterface
     */
    public function createAbstractProductRelationDeleter()
    {
        return new AbstractProductRelationDeleter(
            $this->getQueryContainer(),
            $this->createAbstractProductRelationTouchManager()
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\Touch\AbstractProductRelationTouchManagerInterface
     */
    protected function createAbstractProductRelationTouchManager()
    {
        return new AbstractProductRelationTouchManager($this->getTouchFacade());
    }

}
