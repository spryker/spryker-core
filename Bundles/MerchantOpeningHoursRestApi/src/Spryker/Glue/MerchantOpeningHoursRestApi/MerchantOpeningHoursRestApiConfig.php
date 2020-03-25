<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class MerchantOpeningHoursRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_MERCHANT_OPENING_HOURS = 'merchant-opening-hours';

    /**
     * @uses \Spryker\Glue\MerchantsRestApi\MerchantsRestApiConfig::RESOURCE_MERCHANTS
     */
    public const RESOURCE_MERCHANTS = 'merchants';

    /**
     * @uses \Spryker\Glue\MerchantsRestApi\MerchantsRestApiConfig::RESPONSE_CODE_MERCHANT_NOT_FOUND
     */
    public const RESPONSE_CODE_MERCHANT_NOT_FOUND = '3501';
    /**
     * @uses \Spryker\Glue\MerchantsRestApi\MerchantsRestApiConfig::RESPONSE_DETAIL_MERCHANT_NOT_FOUND
     */
    public const RESPONSE_DETAIL_MERCHANT_NOT_FOUND = 'Merchant not found.';

    /**
     * @uses \Spryker\Glue\MerchantsRestApi\MerchantsRestApiConfig::RESPONSE_CODE_MERCHANT_IDENTIFIER_MISSING
     */
    public const RESPONSE_CODE_MERCHANT_IDENTIFIER_MISSING = '3502';
    /**
     * @uses \Spryker\Glue\MerchantsRestApi\MerchantsRestApiConfig::RESPONSE_DETAIL_MERCHANT_IDENTIFIER_MISSING
     */
    public const RESPONSE_DETAIL_MERCHANT_IDENTIFIER_MISSING = 'Merchant identifier is not specified.';
}
