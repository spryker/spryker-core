<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class MerchantsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RESOURCE_MERCHANTS = 'merchants';
    /**
     * @var string
     */
    public const RESOURCE_MERCHANT_ADDRESSES = 'merchant-addresses';

    /**
     * @var string
     */
    public const RESPONSE_CODE_MERCHANT_NOT_FOUND = '3501';
    /**
     * @var string
     */
    public const RESPONSE_DETAIL_MERCHANT_NOT_FOUND = 'Merchant not found.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_MERCHANT_IDENTIFIER_MISSING = '3502';
    /**
     * @var string
     */
    public const RESPONSE_DETAIL_MERCHANT_IDENTIFIER_MISSING = 'Merchant identifier is not specified.';
}
