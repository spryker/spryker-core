<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Module\PathBuilder;

use Generated\Shared\Transfer\ModuleTransfer;

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
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return array
     */
    public function buildPaths(ModuleTransfer $moduleTransfer): array
    {
        $moduleFilePaths = [];
        foreach ($this->pathBuilder as $pathBuilder) {
            if (!$pathBuilder->accept($moduleTransfer)) {
                continue;
            }
            $moduleFilePaths = array_merge($moduleFilePaths, $pathBuilder->buildPaths($moduleTransfer));
        }

        return $moduleFilePaths;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return bool
     */
    public function accept(ModuleTransfer $moduleTransfer): bool
    {
        return true;
    }
}
