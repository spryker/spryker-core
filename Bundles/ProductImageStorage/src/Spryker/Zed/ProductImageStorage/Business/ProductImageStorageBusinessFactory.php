<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductImageStorage\Business\Storage\ProductAbstractImageStorageWriter;
use Spryker\Zed\ProductImageStorage\Business\Storage\ProductConcreteImageStorageWriter;
use Spryker\Zed\ProductImageStorage\ProductImageStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductImageStorage\ProductImageStorageConfig getConfig()
 * @method \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageRepositoryInterface getRepository()
 */
class ProductImageStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductImageStorage\Business\Storage\ProductAbstractImageStorageWriterInterface
     */
    public function createProductAbstractImageWriter()
    {
        return new ProductAbstractImageStorageWriter(
            $this->getProductImageFacade(),
            $this->getQueryContainer(),
            $this->getRepository(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Zed\ProductImageStorage\Business\Storage\ProductConcreteImageStorageWriterInterface
     */
    public function createProductConcreteImageWriter()
    {
        return new ProductConcreteImageStorageWriter(
            $this->getProductImageFacade(),
            $this->getQueryContainer(),
            $this->getRepository(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Zed\ProductImageStorage\Dependency\Facade\ProductImageStorageToProductImageBridge
     */
    protected function getProductImageFacade()
    {
        return $this->getProvidedDependency(ProductImageStorageDependencyProvider::FACADE_PRODUCT_IMAGE);
    }
}
