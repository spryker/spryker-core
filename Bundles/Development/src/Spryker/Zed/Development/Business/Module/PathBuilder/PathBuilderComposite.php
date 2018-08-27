<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Module\PathBuilder;

class PathBuilderComposite implements PathBuilderInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\Module\PathBuilder\PathBuilderInterface[]
     */
    protected $pathBuilder;

    /**
     * @param \Spryker\Zed\Development\Business\Module\PathBuilder\PathBuilderInterface[] $pathBuilder
     */
    public function __construct(array $pathBuilder)
    {
        $this->pathBuilder = $pathBuilder;
    }

    /**
     * @param string $module
     *
     * @return array
     */
    public function buildPaths(string $module): array
    {
        $moduleFilePaths = [];
        foreach ($this->pathBuilder as $pathBuilder) {
            $moduleFilePaths = array_merge($moduleFilePaths, $pathBuilder->buildPaths($module));
        }

        return $moduleFilePaths;
    }
}
