<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\Finder;

interface FinderInterface
{
    /**
     * @param string $module
     *
     * @return \Symfony\Component\Finder\SplFileInfo[]
     */
    public function find(string $module): array;
}
