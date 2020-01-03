<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentBannersRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ContentBannersRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_CONTENT_BANNERS = 'content-banners';
    public const CONTROLLER_CONTENT_BANNERS = 'content-banners-resource';

    public const RESPONSE_CODE_CONTENT_NOT_FOUND = '2201';
    public const RESPONSE_DETAILS_CONTENT_NOT_FOUND = 'Content not found.';

    public const RESPONSE_CODE_CONTENT_KEY_IS_MISSING = '2202';
    public const RESPONSE_DETAILS_CONTENT_KEY_IS_MISSING = 'Content key is missing.';

    public const RESPONSE_CODE_CONTENT_TYPE_INVALID = '2203';
    public const RESPONSE_DETAILS_CONTENT_TYPE_INVALID = 'Content type is invalid.';
}
