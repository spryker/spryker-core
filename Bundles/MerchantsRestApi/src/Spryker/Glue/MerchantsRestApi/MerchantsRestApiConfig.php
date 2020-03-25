<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class MerchantsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_MERCHANTS = 'merchants';
    public const RESOURCE_MERCHANT_ADDRESSES = 'merchant-addresses';

    public const RESPONSE_CODE_MERCHANT_NOT_FOUND = '3501';
    public const RESPONSE_DETAIL_MERCHANT_NOT_FOUND = 'Merchant not found.';

    public const RESPONSE_CODE_MERCHANT_IDENTIFIER_MISSING = '3502';
    public const RESPONSE_DETAIL_MERCHANT_IDENTIFIER_MISSING = 'Merchant identifier is not specified.';
}
