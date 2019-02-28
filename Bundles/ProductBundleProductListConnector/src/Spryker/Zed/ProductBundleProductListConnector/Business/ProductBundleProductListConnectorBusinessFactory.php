<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\ProductListExpander;
use Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\ProductListExpanderInterface;
use Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface;
use Spryker\Zed\ProductBundleProductListConnector\ProductBundleProductListConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductBundleProductListConnector\ProductBundleProductListConnectorConfig getConfig()
 */
class ProductBundleProductListConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\ProductListExpanderInterface
     */
    public function createProductListExpander(): ProductListExpanderInterface
    {
        return new ProductListExpander(
            $this->getConfig(),
            $this->getFacadeProductBundle()
        );
    }

    /**
     * @return \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface
     */
    public function getFacadeProductBundle(): ProductBundleProductListConnectorToProductBundleFacadeInterface
    {
        return $this->getProvidedDependency(ProductBundleProductListConnectorDependencyProvider::FACADE_PRODUCT_BUNDLE);
    }
}
