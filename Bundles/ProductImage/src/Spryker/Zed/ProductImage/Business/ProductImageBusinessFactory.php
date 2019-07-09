<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductImage\Business\Model\ProductImageSetCombiner;
use Spryker\Zed\ProductImage\Business\Model\Reader;
use Spryker\Zed\ProductImage\Business\Model\Writer;
use Spryker\Zed\ProductImage\Business\Reader\ProductImageBulkReader;
use Spryker\Zed\ProductImage\Business\Reader\ProductImageBulkReaderInterface;
use Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferMapper;
use Spryker\Zed\ProductImage\ProductImageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductImage\ProductImageConfig getConfig()
 * @method \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductImage\Persistence\ProductImageRepositoryInterface getRepository()
 */
class ProductImageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductImage\Business\Model\ReaderInterface
     */
    public function createProductImageReader()
    {
        return new Reader(
            $this->getQueryContainer(),
            $this->createTransferGenerator(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductImage\Business\Reader\ProductImageBulkReaderInterface
     */
    public function createProductImageBulkReader(): ProductImageBulkReaderInterface
    {
        return new ProductImageBulkReader($this->getRepository(), $this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\ProductImage\Business\Model\WriterInterface
     */
    public function createProductImageWriter()
    {
        return new Writer(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferMapperInterface
     */
    public function createTransferGenerator()
    {
        return new ProductImageTransferMapper(
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductImage\Business\Model\ProductImageSetCombinerInterface
     */
    public function createProductImageSetCombiner()
    {
        return new ProductImageSetCombiner(
            $this->getQueryContainer(),
            $this->createTransferGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductImageDependencyProvider::FACADE_LOCALE);
    }
}
