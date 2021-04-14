<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferWishlistRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantProductOfferWishlistRestApi\Business\Deleter\MerchantProductOfferWishlistRestApiDeleter;
use Spryker\Zed\MerchantProductOfferWishlistRestApi\Business\Deleter\MerchantProductOfferWishlistRestApiDeleterInterface;
use Spryker\Zed\MerchantProductOfferWishlistRestApi\Dependency\Facade\MerchantProductOfferWishlistRestApiToWishlistFacadeInterface;
use Spryker\Zed\MerchantProductOfferWishlistRestApi\MerchantProductOfferWishlistRestApiDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantProductOfferWishlistRestApi\MerchantProductOfferWishlistRestApiConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOfferWishlistRestApi\Persistence\MerchantProductOfferWishlistRestApiEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantProductOfferWishlistRestApi\Persistence\MerchantProductOfferWishlistRestApiRepositoryInterface getRepository()
 */
class MerchantProductOfferWishlistRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductOfferWishlistRestApi\Business\Deleter\MerchantProductOfferWishlistRestApiDeleterInterface
     */
    public function createMerchantProductOfferWishlistRestApiDeleter(): MerchantProductOfferWishlistRestApiDeleterInterface
    {
        return new MerchantProductOfferWishlistRestApiDeleter(
            $this->getWishlistFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProductOfferWishlistRestApi\Dependency\Facade\MerchantProductOfferWishlistRestApiToWishlistFacadeInterface
     */
    public function getWishlistFacade(): MerchantProductOfferWishlistRestApiToWishlistFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferWishlistRestApiDependencyProvider::FACADE_WISHLIST);
    }
}
