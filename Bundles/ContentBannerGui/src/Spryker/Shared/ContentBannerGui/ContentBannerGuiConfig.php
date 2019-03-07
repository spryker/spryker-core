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
}
