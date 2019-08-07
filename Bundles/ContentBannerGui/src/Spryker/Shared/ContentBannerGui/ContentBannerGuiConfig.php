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
     * @uses \Spryker\Shared\ContentBanner\ContentBannerConfig::TWIG_FUNCTION_NAME
     */
    protected const TWIG_FUNCTION_NAME = 'content_banner';

    /**
     * @uses \Spryker\Shared\ContentBanner\ContentBannerConfig::WIDGET_TEMPLATE_IDENTIFIER_BOTTOM_TITLE
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_BOTTOM_TITLE = 'bottom-title';

    /**
     * Content item banner bottom-title template name
     */
    protected const WIDGET_TEMPLATE_DISPLAY_NAME_BOTTOM_TITLE = 'content_banner.template.bottom-title';

    /**
     * @uses \Spryker\Shared\ContentBanner\ContentBannerConfig::WIDGET_TEMPLATE_IDENTIFIER_TOP_TITLE
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_TOP_TITLE = 'top-title';

    /**
     * Content item banner top-title template name
     */
    protected const WIDGET_TEMPLATE_DISPLAY_NAME_TOP_TITLE = 'content_banner.template.top-title';

    /**
     * @return array
     */
    public function getContentWidgetTemplates(): array
    {
        return [
            static::WIDGET_TEMPLATE_IDENTIFIER_BOTTOM_TITLE => static::WIDGET_TEMPLATE_DISPLAY_NAME_BOTTOM_TITLE,
            static::WIDGET_TEMPLATE_IDENTIFIER_TOP_TITLE => static::WIDGET_TEMPLATE_DISPLAY_NAME_TOP_TITLE,
        ];
    }

    /**
     * @return string
     */
    public function getTwigFunctionName(): string
    {
        return static::TWIG_FUNCTION_NAME;
    }
}
