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

    /** @deprecated Price mode check is moved to validation plugin. This response code will be removed in next major release. */
    public const RESPONSE_CODE_INVALID_CURRENCY = '501';
    /** @deprecated Price mode check is moved to validation plugin. This response code will be removed in next major release. */
    public const RESPONSE_CODE_INVALID_PRICE_MODE = '502';
    /** @deprecated Currency check is moved to validation plugin. This response message will be removed in next major release. */
    public const RESPONSE_DETAIL_INVALID_CURRENCY = 'Invalid currency.';
    /** @deprecated Currency check is moved to validation plugin. This response message will be removed in next major release. */
    public const RESPONSE_DETAIL_INVALID_PRICE_MODE = 'Invalid price mode.';

    /** @deprecated Currency parameter handling is moved to BeforeAction plugin. Not used anymore. */
    public const CURRENCY_STRING_PARAMETER = 'currency';
    /** @deprecated Price mode parameter handling is moved to BeforeAction plugin. Not used anymore. */
    public const PRICE_MODE_STRING_PARAMETER = 'priceMode';
    public const QUERY_STRING_PARAMETER = 'q';
}
