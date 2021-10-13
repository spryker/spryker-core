<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferWishlist;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MerchantProductOfferWishlist\Expander\MerchantProductOfferWishlistExpander;
use Spryker\Client\MerchantProductOfferWishlist\Expander\MerchantProductOfferWishlistExpanderInterface;

class MerchantProductOfferWishlistFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\MerchantProductOfferWishlist\Expander\MerchantProductOfferWishlistExpanderInterface
     */
    public function createMerchantProductOfferWishlistExpander(): MerchantProductOfferWishlistExpanderInterface
    {
        return new MerchantProductOfferWishlistExpander();
    }
}
