<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ContentBannerGui;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ContentBannerGuiConfig extends AbstractSharedConfig
{
    /**
     * @uses \Spryker\Shared\ContentBanner\ContentBannerConfig::CONTENT_TYPE_BANNER
     *
     * Content item banner
     */
    public const CONTENT_TYPE_BANNER = 'Banner';

    /**
     * @uses \Spryker\Shared\ContentBanner\ContentBannerConfig::CONTENT_TERM_BANNER
     *
     * Content item banner
     */
    public const CONTENT_TERM_BANNER = 'Banner';

    /**
     * @uses \Spryker\Shared\ContentBanner\ContentBannerConfig::FUNCTION_NAME
     */
    public const FUNCTION_NAME = 'content_banner';

    /**
     * @uses \Spryker\Shared\ContentBanner\ContentBannerConfig::TEMPLATE_IDENTIFIER_DEFAULT
     */
    public const TEMPLATE_IDENTIFIER_DEFAULT = 'default';

    /**
     * @uses \Spryker\Shared\ContentBanner\ContentBannerConfig::TEMPLATE_IDENTIFIER_TOP_TITLE
     */
    public const TEMPLATE_IDENTIFIER_TOP_TITLE = 'top-title';

    /**
     * @uses \Spryker\Shared\ContentBanner\ContentBannerConfig::TEMPLATE_NAMES
     */
    public const TEMPLATE_NAMES = [
        self::TEMPLATE_IDENTIFIER_DEFAULT => 'content_banner.template.default',
        self::TEMPLATE_IDENTIFIER_TOP_TITLE => 'content_banner.template.top-title',
    ];
}
