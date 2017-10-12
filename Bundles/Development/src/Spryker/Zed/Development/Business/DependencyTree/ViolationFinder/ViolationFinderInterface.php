<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\ViolationFinder;

interface ViolationFinderInterface
{
    /**
     * @param array $dependency
     *
     * @return bool
     */
    public function isViolation(array $dependency);
}
