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
     * @var string
     */
    protected $basePath;

    /**
     * @var array
     */
    protected $applications;

    /**
     * @param string $basePath
     * @param array $applications
     */
    public function __construct($basePath, array $applications)
    {
        $this->basePath = $basePath;
        $this->applications = $applications;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return array
     */
    public function buildPaths(ModuleTransfer $moduleTransfer): array
    {
        $paths = [];
        foreach ($this->applications as $application) {
            $paths[] = sprintf('%s/%s/src/SprykerSdk/%s/%s', $this->basePath, $moduleTransfer->getName(), $application, $moduleTransfer->getName());
            $paths[] = sprintf('%s/%s/tests/SprykerSdkTest/%s/%s', $this->basePath, $moduleTransfer->getName(), $application, $moduleTransfer->getName());
        }

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
