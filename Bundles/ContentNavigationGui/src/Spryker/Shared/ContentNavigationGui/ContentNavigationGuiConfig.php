<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ContentNavigationGui;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ContentNavigationGuiConfig extends AbstractSharedConfig
{
    /**
     * @uses \Spryker\Shared\ContentNavigation\ContentNavigationConfig::CONTENT_TYPE_NAVIGATION
     *
     * Content item navigation
     */
    public const CONTENT_TYPE_NAVIGATION = 'Navigation';

    /**
     * @uses \Spryker\Shared\ContentNavigation\ContentNavigationConfig::CONTENT_TERM_NAVIGATION
     *
     * Content item navigation
     */
    public const CONTENT_TERM_NAVIGATION = 'Navigation';
}
