<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree;

interface DependencyTreeBuilderInterface
{
    /**
     * @param \Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\AbstractDependencyFinder|array $dependencyChecker
     *
     * @return $this
     */
    public function addDependencyChecker($dependencyChecker);

    /**
     * @param string $module
     *
     * @return array
     */
    public function buildDependencyTree(string $module): array;
}
