<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedNavigation;

interface ZedNavigationConstants
{

    /**
     * Specification:
     * - If this is set to true, navigation will be loaded from cache.
     * - Default is true, can be set to false in development or for testing.
     *
     * @api
     */
    const ZED_NAVIGATION_CACHE_ENABLED = 'ZED_NAVIGATION_CACHE_ENABLED';

    const ZED_NAVIGATION_ENABLED = 'ZED_NAVIGATION_ENABLED';

}
