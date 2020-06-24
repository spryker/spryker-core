<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesContentBannersResourceRelationship;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CmsPagesContentBannersResourceRelationshipConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Shared\ContentBanner\ContentBannerConfig::TWIG_FUNCTION_NAME
     */
    public const TWIG_FUNCTION_NAME = 'content_banner';

    /**
     * @uses \Spryker\Glue\ContentBannersRestApi\ContentBannersRestApiConfig::RESOURCE_CONTENT_BANNERS
     */
    public const RESOURCE_CONTENT_BANNERS = 'content-banners';
}
