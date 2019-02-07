<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Module\PathBuilder;

use Generated\Shared\Transfer\ModuleTransfer;

class SprykerSdkPathBuilder extends AbstractPathBuilder
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
        $paths = [];
        $basePath = $this->config->getPathToInternalNamespace(static::ORGANIZATION);
        foreach ($this->config->getApplications() as $application) {
            $paths = [
                sprintf('%s/%s/src/SprykerSdk/%s/%s', $basePath, $this->getModuleName($moduleTransfer), $application, $moduleTransfer->getName()),
                sprintf('%s/%s/tests/SprykerSdkTest/%s/%s', $basePath, $this->getModuleName($moduleTransfer), $application, $moduleTransfer->getName()),
            ];
        }

        return $paths;
    }
}
