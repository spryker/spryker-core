<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\Finder;

interface FinderCompositeInterface extends FinderInterface
{
    /**
     * @param \Spryker\Zed\Development\Business\DependencyTree\Finder\FinderInterface $finder
     *
     * @return \Spryker\Zed\Development\Business\DependencyTree\Finder\FinderCompositeInterface
     */
    public function addFinder(FinderInterface $finder): self;
}
