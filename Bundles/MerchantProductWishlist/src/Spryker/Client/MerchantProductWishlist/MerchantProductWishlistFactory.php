<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductWishlist;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MerchantProductWishlist\Expander\MerchantProductWishlistExpander;
use Spryker\Client\MerchantProductWishlist\Expander\MerchantProductWishlistExpanderInterface;

class MerchantProductWishlistFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\MerchantProductWishlist\Expander\MerchantProductWishlistExpanderInterface
     */
    public function createMerchantProductWishlistExpander(): MerchantProductWishlistExpanderInterface
    {
        return new MerchantProductWishlistExpander();
    }
}
