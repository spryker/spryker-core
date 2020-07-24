<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferAvailabilitiesRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ProductOfferAvailabilitiesRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_PRODUCT_OFFER_AVAILABILITIES = 'product-offer-availabilities';

    /**
     * @uses \Spryker\Glue\MerchantProductOffersRestApi\MerchantProductOffersRestApiConfig::RESOURCE_PRODUCT_OFFERS
     */
    public const RESOURCE_PRODUCT_OFFERS = 'product-offers';

    /**
     * @uses \Spryker\Glue\MerchantProductOffersRestApi\MerchantProductOffersRestApiConfig::RESPONSE_CODE_PRODUCT_OFFER_ID_IS_NOT_SPECIFIED
     */
    public const RESPONSE_CODE_PRODUCT_OFFER_ID_IS_NOT_SPECIFIED = '3702';

    /**
     * @uses \Spryker\Glue\MerchantProductOffersRestApi\MerchantProductOffersRestApiConfig::RESPONSE_DETAIL_PRODUCT_OFFER_ID_SKU_IS_NOT_SPECIFIED
     */
    public const RESPONSE_DETAIL_PRODUCT_OFFER_ID_SKU_IS_NOT_SPECIFIED = 'Product offer ID is not specified.';
}
