<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductWishlist\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantProductWishlist\Communication\Expander\MerchantProductWishlistItemExpander;
use Spryker\Zed\MerchantProductWishlist\Communication\Expander\MerchantProductWishlistItemExpanderInterface;
use Spryker\Zed\MerchantProductWishlist\Dependency\Facade\MerchantProductWishlistToMerchantProductFacadeInterface;
use Spryker\Zed\MerchantProductWishlist\Dependency\Facade\MerchantProductWishlistToProductFacadeInterface;
use Spryker\Zed\MerchantProductWishlist\MerchantProductWishlistDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantProductWishlist\MerchantProductWishlistConfig getConfig()
 */
class MerchantProductWishlistCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductWishlist\Communication\Expander\MerchantProductWishlistItemExpanderInterface
     */
    public function createMerchantProductWishlistItemExpander(): MerchantProductWishlistItemExpanderInterface
    {
        return new MerchantProductWishlistItemExpander(
            $this->getProductFacade(),
            $this->getMerchantProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProductWishlist\Dependency\Facade\MerchantProductWishlistToProductFacadeInterface
     */
    public function getProductFacade(): MerchantProductWishlistToProductFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductWishlistDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\MerchantProductWishlist\Dependency\Facade\MerchantProductWishlistToMerchantProductFacadeInterface
     */
    public function getMerchantProductFacade(): MerchantProductWishlistToMerchantProductFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductWishlistDependencyProvider::FACADE_MERCHANT_PRODUCT);
    }
}
