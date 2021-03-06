<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferWishlistRestApi\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantProductOfferWishlistRestApi\Communication\Reader\MerchantProductOfferWishlistRestApiReader;
use Spryker\Zed\MerchantProductOfferWishlistRestApi\Communication\Reader\MerchantProductOfferWishlistRestApiReaderInterface;

/**
 * @method \Spryker\Zed\MerchantProductOfferWishlistRestApi\MerchantProductOfferWishlistRestApiConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOfferWishlistRestApi\Business\MerchantProductOfferWishlistRestApiFacadeInterface getFacade()
 */
class MerchantProductOfferWishlistRestApiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductOfferWishlistRestApi\Communication\Reader\MerchantProductOfferWishlistRestApiReaderInterface
     */
    public function createMerchantProductOfferWishlistRestApiReader(): MerchantProductOfferWishlistRestApiReaderInterface
    {
        return new MerchantProductOfferWishlistRestApiReader();
    }
}
