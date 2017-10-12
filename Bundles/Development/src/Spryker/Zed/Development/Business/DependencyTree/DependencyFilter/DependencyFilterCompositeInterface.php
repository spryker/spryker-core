<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyFilter;

interface DependencyFilterCompositeInterface extends DependencyFilterInterface
{
    /**
     * @param \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\DependencyFilterInterface $dependencyFilter
     *
     * @return $this
     */
    public function addFilter(DependencyFilterInterface $dependencyFilter);
}
