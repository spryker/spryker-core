<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class UrlsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_URLS = 'urls';

    public const RESPONSE_CODE_URL_REQUEST_PARAMETER_MISSING = '2801';
    public const RESPONSE_DETAIL_URL_REQUEST_PARAMETER_MISSING = 'Url request parameter is missing.';

    public const RESPONSE_CODE_URL_NOT_FOUND = '2802';
    public const RESPONSE_DETAIL_URL_NOT_FOUND = 'Url not found.';
}
