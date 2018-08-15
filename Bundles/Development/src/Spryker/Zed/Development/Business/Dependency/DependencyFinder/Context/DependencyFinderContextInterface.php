<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyFinder\Context;

use Symfony\Component\Finder\SplFileInfo;

interface DependencyFinderContextInterface
{
    /**
     * @return string
     */
    public function getModule(): string;

    /**
     * @return \Symfony\Component\Finder\SplFileInfo
     */
    public function getFileInfo(): SplFileInfo;

    /**
     * @return string|null
     */
    public function getDependencyType(): ?string;
}
