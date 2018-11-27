<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CatalogSearchRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CatalogSearchRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_CATALOG_SEARCH = 'catalog-search';
    public const RESOURCE_CATALOG_SEARCH_SUGGESTIONS = 'catalog-search-suggestions';

    public const RESPONSE_CODE_INVALID_CURRENCY = '501';
    public const RESPONSE_CODE_INVALID_PRICE_MODE = '502';
    public const RESPONSE_DETAIL_INVALID_CURRENCY = 'Invalid currency.';
    public const RESPONSE_DETAIL_INVALID_PRICE_MODE = 'Invalid price mode.';

    public const CURRENCY_STRING_PARAMETER = 'currency';
    public const PRICE_MODE_STRING_PARAMETER = 'priceMode';
    public const QUERY_STRING_PARAMETER = 'q';
}
