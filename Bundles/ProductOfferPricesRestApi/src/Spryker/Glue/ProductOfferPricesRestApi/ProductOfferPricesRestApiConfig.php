<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferPricesRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ProductOfferPricesRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_PRODUCT_OFFER_PRICES = 'product-offer-prices';

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

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_NET
     */
    public const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     */
    public const PRICE_MODE_GROSS = 'GROSS_MODE';
}
