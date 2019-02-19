<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductBundleConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductDiscontinuedProductBundleConnector\Business\ProductBundleDiscontinued\ProductBundleDiscontinuedReader;
use Spryker\Zed\ProductDiscontinuedProductBundleConnector\Business\ProductBundleDiscontinued\ProductBundleDiscontinuedWriter;
use Spryker\Zed\ProductDiscontinuedProductBundleConnector\Business\ProductBundleDiscontinued\ProductBundleDiscontinuedWriterInterface;
use Spryker\Zed\ProductDiscontinuedProductBundleConnector\Dependency\Facade\ProductDiscontinuedProductBundleConnectorToProductBundleFacadeInterface;
use Spryker\Zed\ProductDiscontinuedProductBundleConnector\Dependency\Facade\ProductDiscontinuedProductBundleConnectorToProductDiscontinuedFacadeInterface;
use Spryker\Zed\ProductDiscontinuedProductBundleConnector\ProductDiscontinuedProductBundleConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductDiscontinuedProductBundleConnector\ProductDiscontinuedProductBundleConnectorConfig getConfig()
 * @method \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Business\ProductDiscontinuedProductBundleConnectorBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Persistence\ProductDiscontinuedProductBundleConnectorRepositoryInterface getRepository()
 */
class ProductDiscontinuedProductBundleConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Business\ProductBundleDiscontinued\ProductBundleDiscontinuedWriterInterface
     */
    public function createProductBundleDiscontinuedWriter(): ProductBundleDiscontinuedWriterInterface
    {
        return new ProductBundleDiscontinuedWriter(
            $this->getRepository(),
            $this->getProductDiscontinuedFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Business\ProductBundleDiscontinued\ProductBundleDiscontinuedReader
     */
    public function createProductBundleDiscontinuedReader(): ProductBundleDiscontinuedReader
    {
        return new ProductBundleDiscontinuedReader(
            $this->getProductDiscontinuedFacade(),
            $this->getProductBundleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Dependency\Facade\ProductDiscontinuedProductBundleConnectorToProductDiscontinuedFacadeInterface
     */
    public function getProductDiscontinuedFacade(): ProductDiscontinuedProductBundleConnectorToProductDiscontinuedFacadeInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedProductBundleConnectorDependencyProvider::FACADE_PRODUCT_DISCONTINUED);
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Dependency\Facade\ProductDiscontinuedProductBundleConnectorToProductBundleFacadeInterface
     */
    public function getProductBundleFacade(): ProductDiscontinuedProductBundleConnectorToProductBundleFacadeInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedProductBundleConnectorDependencyProvider::FACADE_PRODUCT_BUNDLE);
    }
}
