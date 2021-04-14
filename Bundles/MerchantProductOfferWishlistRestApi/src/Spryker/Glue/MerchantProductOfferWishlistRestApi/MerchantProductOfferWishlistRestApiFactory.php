<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductOfferWishlistRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\MerchantProductOfferWishlistRestApi\Processor\Mapper\Wishlist\MerchantProductOfferWishlistRestApiMapper;
use Spryker\Glue\MerchantProductOfferWishlistRestApi\Processor\Mapper\Wishlist\MerchantProductOfferWishlistRestApiMapperInterface;

class MerchantProductOfferWishlistRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\MerchantProductOfferWishlistRestApi\Processor\Mapper\Wishlist\MerchantProductOfferWishlistRestApiMapperInterface
     */
    public function createMerchantProductOfferWishlistRestApiMapper(): MerchantProductOfferWishlistRestApiMapperInterface
    {
        return new MerchantProductOfferWishlistRestApiMapper();
    }
}
