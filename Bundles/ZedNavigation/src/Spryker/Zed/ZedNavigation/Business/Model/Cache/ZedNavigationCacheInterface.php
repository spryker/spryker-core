<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model\Cache;

interface ZedNavigationCacheInterface
{
    /**
     * @return bool
     */
    public function isEnabled();

    /**
     * @param array $navigation
     *
     * @return void
     */
    public function setNavigation(array $navigation);

    /**
     * @return array
     */
    public function getNavigation();

    /**
     * @return bool
     */
    public function hasContent(): bool;
}
