<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ContentBanner;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ContentBannerConfig extends AbstractSharedConfig
{
    /**
     * Content item banner
     */
    public const CONTENT_TYPE_BANNER = 'Banner';

    /**
     * Content item banner
     */
    public const CONTENT_TERM_BANNER = 'Banner';

    /**
     * Content item banner function name
     */
    public const TWIG_FUNCTION_NAME = 'content_banner';

    /**
     * Content item banner default template identifier
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_DEFAULT = 'default';

    /**
     * Content item banner default template name
     */
    protected const WIDGET_TEMPLATE_DISPLAY_NAME_DEFAULT = 'content_banner.template.default';

    /**
     * Content item banner top-title template identifier
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_TOP_TITLE = 'top-title';

    /**
     * Content item banner top-title template name
     */
    protected const WIDGET_TEMPLATE_DISPLAY_NAME_TOP_TITLE = 'content_banner.template.top-title';
}
