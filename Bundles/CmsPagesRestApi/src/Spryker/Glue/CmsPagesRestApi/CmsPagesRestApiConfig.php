<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CmsPagesRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RESOURCE_CMS_PAGES = 'cms-pages';

    /**
     * @var string
     */
    public const QUERY_STRING_PARAMETER = 'q';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CMS_PAGE_NOT_FOUND = '3801';
    /**
     * @var string
     */
    public const RESPONSE_DETAIL_CMS_PAGE_NOT_FOUND = 'Cms page not found.';
}
