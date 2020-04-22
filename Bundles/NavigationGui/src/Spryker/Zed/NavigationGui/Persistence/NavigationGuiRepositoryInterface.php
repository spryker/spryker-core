<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Persistence;

interface NavigationGuiRepositoryInterface
{
    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasNavigationKey(string $key): bool;
}
