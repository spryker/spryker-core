<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferWishlist\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantProductOfferWishlist\Communication\Expander\MerchantProductOfferWishlistItemExpander;
use Spryker\Zed\MerchantProductOfferWishlist\Communication\Expander\MerchantProductOfferWishlistItemExpanderInterface;
use Spryker\Zed\MerchantProductOfferWishlist\Dependency\Facade\MerchantProductOfferWishlistToMerchantFacadeInterface;
use Spryker\Zed\MerchantProductOfferWishlist\MerchantProductOfferWishlistDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantProductOfferWishlist\Persistence\MerchantProductOfferWishlistRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProductOfferWishlist\MerchantProductOfferWishlistConfig getConfig()
 */
class MerchantProductOfferWishlistCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductOfferWishlist\Communication\Expander\MerchantProductOfferWishlistItemExpanderInterface
     */
    public function createMerchantProductOfferWishlistItemExpander(): MerchantProductOfferWishlistItemExpanderInterface
    {
        return new MerchantProductOfferWishlistItemExpander(
            $this->getRepository(),
            $this->getMerchantFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProductOfferWishlist\Dependency\Facade\MerchantProductOfferWishlistToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantProductOfferWishlistToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferWishlistDependencyProvider::FACADE_MERCHANT);
    }
}
