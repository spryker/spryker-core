<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyFinder\Context;

use Symfony\Component\Finder\SplFileInfo;

class DependencyFinderContext implements DependencyFinderContextInterface
{
    /**
     * @var string
     */
    protected $module;

    /**
     * @var \Symfony\Component\Finder\SplFileInfo
     */
    protected $fileInfo;

    /**
     * @var string|null
     */
    protected $dependencyType;

    /**
     * @param string $module
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     * @param string|null $dependencyType
     */
    public function __construct(string $module, SplFileInfo $fileInfo, ?string $dependencyType = null)
    {
        $this->module = $module;
        $this->fileInfo = $fileInfo;
        $this->dependencyType = $dependencyType;
    }

    /**
     * @return string
     */
    public function getModule(): string
    {
        return $this->module;
    }

    /**
     * @return \Symfony\Component\Finder\SplFileInfo
     */
    public function getFileInfo(): SplFileInfo
    {
        return $this->fileInfo;
    }

    /**
     * @return null|string
     */
    public function getDependencyType(): ?string
    {
        return $this->dependencyType;
    }
}
