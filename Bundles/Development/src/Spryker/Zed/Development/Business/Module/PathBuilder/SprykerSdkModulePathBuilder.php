<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Module\PathBuilder;

use Generated\Shared\Transfer\ModuleTransfer;

class SprykerSdkModulePathBuilder extends AbstractPathBuilder
{
    /**
     * @var string
     */
    protected const ORGANIZATION = 'SprykerSdk';

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return array
     */
    public function buildPaths(ModuleTransfer $moduleTransfer): array
    {
        $basePath = rtrim($this->config->getPathToInternalNamespace(static::ORGANIZATION), '/');
        $paths = [
            sprintf('%s/%s/src/SprykerSdk/', $basePath, $this->getModuleName($moduleTransfer)),
            sprintf('%s/%s/tests/SprykerSdkTest/', $basePath, $this->getModuleName($moduleTransfer)),
        ];

        foreach ($this->config->getApplications() as $application) {
            $paths[] = sprintf('%s/%s/src/SprykerSdk/%s/%s', $basePath, $this->getModuleName($moduleTransfer), $application, $moduleTransfer->getName());
            $paths[] = sprintf('%s/%s/tests/SprykerSdkTest/%s/%s', $basePath, $this->getModuleName($moduleTransfer), $application, $moduleTransfer->getName());
        }

        return $paths;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return string
     */
    protected function getModuleName(ModuleTransfer $moduleTransfer): string
    {
        return $moduleTransfer->getNameDashed();
    }
}
