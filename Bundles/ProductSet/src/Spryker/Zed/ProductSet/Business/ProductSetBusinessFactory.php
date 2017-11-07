<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductSet\Business\Model\Data\ProductSetDataCreator;
use Spryker\Zed\ProductSet\Business\Model\Data\ProductSetDataDeleter;
use Spryker\Zed\ProductSet\Business\Model\Data\ProductSetDataReader;
use Spryker\Zed\ProductSet\Business\Model\Data\ProductSetDataUpdater;
use Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlCreator;
use Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlDeleter;
use Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlReader;
use Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlUpdater;
use Spryker\Zed\ProductSet\Business\Model\Image\ProductSetImageDeleter;
use Spryker\Zed\ProductSet\Business\Model\Image\ProductSetImageReader;
use Spryker\Zed\ProductSet\Business\Model\Image\ProductSetImageSaver;
use Spryker\Zed\ProductSet\Business\Model\Image\ProductSetImageSetCombiner;
use Spryker\Zed\ProductSet\Business\Model\ProductSetCreator;
use Spryker\Zed\ProductSet\Business\Model\ProductSetDeleter;
use Spryker\Zed\ProductSet\Business\Model\ProductSetEntityReader;
use Spryker\Zed\ProductSet\Business\Model\ProductSetExpander;
use Spryker\Zed\ProductSet\Business\Model\ProductSetOrganizer;
use Spryker\Zed\ProductSet\Business\Model\ProductSetReader;
use Spryker\Zed\ProductSet\Business\Model\ProductSetReducer;
use Spryker\Zed\ProductSet\Business\Model\ProductSetUpdater;
use Spryker\Zed\ProductSet\Business\Model\Touch\ProductSetTouch;
use Spryker\Zed\ProductSet\ProductSetDependencyProvider;

/**
 * @method \Spryker\Zed\ProductSet\ProductSetConfig getConfig()
 * @method \Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface getQueryContainer()
 */
class ProductSetBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductSet\Business\Model\ProductSetCreatorInterface
     */
    public function createProductSetCreator()
    {
        return new ProductSetCreator(
            $this->createProductSetDataCreator(),
            $this->createProductSetTouch(),
            $this->createProductSetImageCreator()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\Model\ProductSetReaderInterface
     */
    public function createProductSetReader()
    {
        return new ProductSetReader(
            $this->getQueryContainer(),
            $this->createProductSetDataReader(),
            $this->createProductSetImageReader()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\Model\ProductSetUpdaterInterface
     */
    public function createProductSetUpdater()
    {
        return new ProductSetUpdater(
            $this->createProductSetEntityReader(),
            $this->createProductSetDataUpdater(),
            $this->createProductSetImageCreator(),
            $this->createProductSetTouch()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\Model\ProductSetExpanderInterface
     */
    public function createProductSetExpander()
    {
        return new ProductSetExpander($this->createProductSetEntityReader(), $this->createProductSetTouch());
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\Model\ProductSetReducerInterface
     */
    public function createProductSetReducer()
    {
        return new ProductSetReducer($this->createProductSetEntityReader(), $this->createProductSetTouch());
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\Model\ProductSetDeleterInterface
     */
    public function createProductSetDeleter()
    {
        return new ProductSetDeleter(
            $this->createProductSetEntityReader(),
            $this->createProductSetDataDeleter(),
            $this->createProductSetImageDeleter(),
            $this->createProductSetTouch()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\Model\ProductSetOrganizerInterface
     */
    public function createProductSetOrganizer()
    {
        return new ProductSetOrganizer($this->createProductSetEntityReader(), $this->createProductSetTouch());
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\Model\Image\ProductSetImageSetCombinerInterface
     */
    public function createProductSetImageSetCombiner()
    {
        return new ProductSetImageSetCombiner(
            $this->getQueryContainer(),
            $this->getProductImageFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\Model\Data\ProductSetDataCreatorInterface
     */
    protected function createProductSetDataCreator()
    {
        return new ProductSetDataCreator($this->createProductSetUrlCreator());
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlCreatorInterface
     */
    protected function createProductSetUrlCreator()
    {
        return new ProductSetUrlCreator($this->getUrlFacade());
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\Model\Image\ProductSetImageSaverInterface
     */
    protected function createProductSetImageCreator()
    {
        return new ProductSetImageSaver($this->getQueryContainer(), $this->getProductImageFacade());
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\Model\Touch\ProductSetTouchInterface
     */
    protected function createProductSetTouch()
    {
        return new ProductSetTouch($this->getTouchFacade(), $this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\Model\Data\ProductSetDataReaderInterface
     */
    protected function createProductSetDataReader()
    {
        return new ProductSetDataReader($this->createProductSetUrlReader());
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlReaderInterface
     */
    protected function createProductSetUrlReader()
    {
        return new ProductSetUrlReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\Model\Image\ProductSetImageReaderInterface
     */
    protected function createProductSetImageReader()
    {
        return new ProductSetImageReader($this->getQueryContainer(), $this->getProductImageFacade());
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\Model\ProductSetEntityReaderInterface
     */
    protected function createProductSetEntityReader()
    {
        return new ProductSetEntityReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\Model\Data\ProductSetDataUpdaterInterface
     */
    protected function createProductSetDataUpdater()
    {
        return new ProductSetDataUpdater($this->getQueryContainer(), $this->createProductSetUrlUpdater());
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlUpdaterInterface
     */
    protected function createProductSetUrlUpdater()
    {
        return new ProductSetUrlUpdater($this->createProductSetUrlReader(), $this->getUrlFacade());
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\Model\Data\ProductSetDataDeleterInterface
     */
    protected function createProductSetDataDeleter()
    {
        return new ProductSetDataDeleter($this->createProductSetUrlDeleter());
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlDeleterInterface
     */
    protected function createProductSetUrlDeleter()
    {
        return new ProductSetUrlDeleter($this->getQueryContainer(), $this->getUrlFacade());
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\Model\Image\ProductSetImageDeleterInterface
     */
    protected function createProductSetImageDeleter()
    {
        return new ProductSetImageDeleter($this->getQueryContainer(), $this->getProductImageFacade());
    }

    /**
     * @return \Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(ProductSetDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToUrlInterface
     */
    protected function getUrlFacade()
    {
        return $this->getProvidedDependency(ProductSetDependencyProvider::FACADE_URL);
    }

    /**
     * @return \Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToProductImageInterface
     */
    protected function getProductImageFacade()
    {
        return $this->getProvidedDependency(ProductSetDependencyProvider::FACADE_PRODUCT_IMAGE);
    }
}
