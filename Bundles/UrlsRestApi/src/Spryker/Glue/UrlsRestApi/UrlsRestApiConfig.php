<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class UrlsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RESOURCE_URL_RESOLVER = 'url-resolver';

    /**
     * @var string
     */
    public const RESPONSE_CODE_URL_REQUEST_PARAMETER_MISSING = '2801';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_URL_REQUEST_PARAMETER_MISSING = 'Url request parameter is missing.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_URL_NOT_FOUND = '2802';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_URL_NOT_FOUND = 'Url not found.';
}
