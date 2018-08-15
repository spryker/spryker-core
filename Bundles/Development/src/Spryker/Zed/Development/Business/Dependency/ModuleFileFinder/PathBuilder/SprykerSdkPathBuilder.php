<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\ModuleFileFinder\PathBuilder;

class SprykerSdkPathBuilder extends AbstractPathBuilder
{
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
     * @param string $module
     *
     * @return array
     */
    public function buildPaths(string $module): array
    {
        $paths = [];
        foreach ($this->applications as $application) {
            $paths[] = sprintf('%s/%s/src/SprykerSdk/%s/%s', $this->basePath, $module, $application, $module);
            $paths[] = sprintf('%s/%s/tests/SprykerSdkTest/%s/%s', $this->basePath, $module, $application, $module);
        }

        return $paths;
    }
}
