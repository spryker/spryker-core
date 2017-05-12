<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductLabel\Business\Label\LabelCreator;
use Spryker\Zed\ProductLabel\Business\Label\LabelReader;
use Spryker\Zed\ProductLabel\Business\Label\LabelUpdater;
use Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionReader;
use Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionWriter;
use Spryker\Zed\ProductLabel\Business\ProductRelation\ProductRelationDeleter;
use Spryker\Zed\ProductLabel\Business\ProductRelation\ProductRelationReader;
use Spryker\Zed\ProductLabel\Business\ProductRelation\ProductRelationWriter;

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
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\Label\LabelUpdaterInterface
     */
    public function createLabelUpdater()
    {
        return new LabelUpdater($this->getQueryContainer());
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
    public function createLocalizedAttributesCollectionWriter()
    {
        return new LocalizedAttributesCollectionWriter();
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionReaderInterface
     */
    public function createLocalizedAttributesCollectionReader()
    {
        return new LocalizedAttributesCollectionReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\ProductRelation\ProductRelationReaderInterface
     */
    public function createProductRelationReader()
    {
        return new ProductRelationReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\ProductRelation\ProductRelationWriterInterface
     */
    public function createProductRelationWriter()
    {
        return new ProductRelationWriter(
            $this->getQueryContainer(),
            $this->createProductRelationDeleter()
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\ProductRelation\ProductRelationDeleterInterface
     */
    public function createProductRelationDeleter()
    {
        return new ProductRelationDeleter($this->getQueryContainer());
    }

}
