<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Module\PathBuilder;

use Generated\Shared\Transfer\ModuleTransfer;

class SprykerEcoModulePathBuilder extends AbstractPathBuilder
{
    /**
     * @var string
     */
    protected const ORGANIZATION = 'SprykerEco';

    /**
     * @var string
     */
    protected $basePath;

    /**
     * @param string $basePath
     */
    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return array
     */
    public function buildPaths(ModuleTransfer $moduleTransfer): array
    {
        return [
            sprintf('%s%s/', $this->basePath, $moduleTransfer->getNameDashed()),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return bool
     */
    public function accept(ModuleTransfer $moduleTransfer): bool
    {
        return ($moduleTransfer->getOrganization()->getName() === static::ORGANIZATION);
    }
}
