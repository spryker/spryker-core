<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageCartConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductImageCartConnector\Business\Expander\ProductImageExpander;
use Spryker\Zed\ProductImageCartConnector\Business\Expander\ProductImageItemExpander;
use Spryker\Zed\ProductImageCartConnector\Business\Expander\ProductImageItemExpanderInterface;
use Spryker\Zed\ProductImageCartConnector\Dependency\Facade\ProductImageCartConnectorToLocaleFacadeInterface;
use Spryker\Zed\ProductImageCartConnector\Dependency\Facade\ProductImageCartConnectorToProductImageFacadeInterface;
use Spryker\Zed\ProductImageCartConnector\ProductImageCartConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductImageCartConnector\Business\ProductImageCartConnectorBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductImageCartConnector\ProductImageCartConnectorConfig getConfig()
 */
class ProductImageCartConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductImageCartConnector\Business\Expander\ProductImageExpanderInterface
     */
    public function createProductExpander()
    {
        return new ProductImageExpander(
            $this->getProductImageFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductImageCartConnector\Business\Expander\ProductImageItemExpanderInterface
     */
    public function createProductImageItemExpander(): ProductImageItemExpanderInterface
    {
        return new ProductImageItemExpander(
            $this->getProductImageFacade(),
            $this->getLocaleFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductImageCartConnector\Dependency\Facade\ProductImageCartConnectorToProductImageFacadeInterface
     */
    public function getProductImageFacade(): ProductImageCartConnectorToProductImageFacadeInterface
    {
        return $this->getProvidedDependency(ProductImageCartConnectorDependencyProvider::FACADE_PRODUCT_IMAGE);
    }

    /**
     * @return \Spryker\Zed\ProductImageCartConnector\Dependency\Facade\ProductImageCartConnectorToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductImageCartConnectorToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductImageCartConnectorDependencyProvider::FACADE_LOCALE);
    }
}
