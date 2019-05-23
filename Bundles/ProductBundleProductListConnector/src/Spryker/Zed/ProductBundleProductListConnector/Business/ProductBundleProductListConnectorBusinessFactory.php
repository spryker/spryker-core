<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\ProductListExpander;
use Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\ProductListExpanderInterface;
use Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type\BlacklistProductListTypeExpander;
use Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type\ProductListTypeExpanderInterface;
use Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type\WhitelistProductListTypeExpander;
use Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface;
use Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductFacadeInterface;
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
        return new ProductListExpander([
            $this->createBlacklistProductListTypeExpander(),
            $this->createWhitelistProductListTypeExpander(),
        ]);
    }

    /**
     * @return \Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type\ProductListTypeExpanderInterface
     */
    public function createBlacklistProductListTypeExpander(): ProductListTypeExpanderInterface
    {
        return new BlacklistProductListTypeExpander(
            $this->getFacadeProductBundle(),
            $this->getFacadeProduct()
        );
    }

    /**
     * @return \Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type\ProductListTypeExpanderInterface
     */
    public function createWhitelistProductListTypeExpander(): ProductListTypeExpanderInterface
    {
        return new WhitelistProductListTypeExpander(
            $this->getFacadeProductBundle(),
            $this->getFacadeProduct()
        );
    }

    /**
     * @return \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductFacadeInterface
     */
    public function getFacadeProduct(): ProductBundleProductListConnectorToProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductBundleProductListConnectorDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface
     */
    public function getFacadeProductBundle(): ProductBundleProductListConnectorToProductBundleFacadeInterface
    {
        return $this->getProvidedDependency(ProductBundleProductListConnectorDependencyProvider::FACADE_PRODUCT_BUNDLE);
    }
}
