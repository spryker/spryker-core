<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationWishlist\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductConfigurationWishlist\Business\Checker\ProductConfigurationChecker;
use Spryker\Zed\ProductConfigurationWishlist\Business\Checker\ProductConfigurationCheckerInterface;
use Spryker\Zed\ProductConfigurationWishlist\Business\Checker\ProductConfigurationWishlistChecker;
use Spryker\Zed\ProductConfigurationWishlist\Business\Checker\ProductConfigurationWishlistCheckerInterface;
use Spryker\Zed\ProductConfigurationWishlist\Business\Expander\ProductConfigurationWishlistItemExpander;
use Spryker\Zed\ProductConfigurationWishlist\Business\Expander\ProductConfigurationWishlistItemExpanderInterface;
use Spryker\Zed\ProductConfigurationWishlist\Dependency\Facade\ProductConfigurationWishlistToProductConfigurationFacadeInterface;
use Spryker\Zed\ProductConfigurationWishlist\Dependency\Service\ProductConfigurationWishlistToUtilEncodingServiceInterface;
use Spryker\Zed\ProductConfigurationWishlist\ProductConfigurationWishlistDependencyProvider;

/**
 * @method \Spryker\Zed\ProductConfigurationWishlist\ProductConfigurationWishlistConfig getConfig()
 */
class ProductConfigurationWishlistBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductConfigurationWishlist\Business\Checker\ProductConfigurationCheckerInterface
     */
    public function createProductConfigurationChecker(): ProductConfigurationCheckerInterface
    {
        return new ProductConfigurationChecker($this->getProductConfigurationFacade());
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationWishlist\Business\Expander\ProductConfigurationWishlistItemExpanderInterface
     */
    public function createProductConfigurationWishlistItemExpander(): ProductConfigurationWishlistItemExpanderInterface
    {
        return new ProductConfigurationWishlistItemExpander($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationWishlist\Business\Checker\ProductConfigurationWishlistCheckerInterface
     */
    public function createProductConfigurationWishlistChecker(): ProductConfigurationWishlistCheckerInterface
    {
        return new ProductConfigurationWishlistChecker();
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationWishlist\Dependency\Facade\ProductConfigurationWishlistToProductConfigurationFacadeInterface
     */
    public function getProductConfigurationFacade(): ProductConfigurationWishlistToProductConfigurationFacadeInterface
    {
        return $this->getProvidedDependency(ProductConfigurationWishlistDependencyProvider::FACADE_PRODUCT_CONFIGURATION);
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationWishlist\Dependency\Service\ProductConfigurationWishlistToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductConfigurationWishlistToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductConfigurationWishlistDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
