<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model\Collector;

interface ZedNavigationCollectorInterface
{
    /**
     * @param string $navigationType
     *
     * @return array
     */
    public function getNavigation(string $navigationType): array;
}
