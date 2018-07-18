<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SearchRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class SearchRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_SEARCH = 'search';
    public const RESOURCE_SUGGESTIONS = 'search-suggestions';

    public const RESPONSE_CODE_INVALID_CURRENCY = '501';
    public const RESPONSE_DETAIL_INVALID_CURRENCY = 'Invalid currency';

    public const CURRENCY_STRING_PARAMETER = 'currency';
}
