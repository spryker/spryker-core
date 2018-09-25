<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Module\PathBuilder;

use Generated\Shared\Transfer\ModuleTransfer;

class SprykerStandaloneModulePathBuilder extends AbstractPathBuilder
{
    /**
     * @var string
     */
    protected const ORGANIZATION = 'Spryker';

    /**
     * @var string
     */
    protected $basePath;

    public function __construct()
    {
        $this->basePath = APPLICATION_VENDOR_DIR . '/spryker/';
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return array
     */
    public function buildPaths(ModuleTransfer $moduleTransfer): array
    {
        $paths = [
            sprintf('%s%s/', $this->basePath, $moduleTransfer->getNameDashed()),
        ];

        return $paths;
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
