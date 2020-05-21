<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductOffersRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class MerchantProductOffersRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_PRODUCT_OFFERS = 'product-offers';

    public const RESPONSE_CODE_PRODUCT_OFFER_NOT_FOUND = '301';
    public const RESPONSE_DETAIL_PRODUCT_OFFER_NOT_FOUND = 'Product offer not found.';
    public const RESPONSE_CODE_PRODUCT_OFFER_ID_IS_NOT_SPECIFIED = '311';
    public const RESPONSE_DETAIL_PRODUCT_OFFER_ID_SKU_IS_NOT_SPECIFIED = 'Product offer ID is not specified.';
}
