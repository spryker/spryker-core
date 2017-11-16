<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\Finder\PathBuilder;

class SprykerPathBuilder implements PathBuilderInterface
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
            $paths[] = sprintf('%s/%s/src/Spryker/%s/%s', $this->basePath, $module, $application, $module);
            $paths[] = sprintf('%s/%s/src/SprykerTest/%s/%s', $this->basePath, $module, $application, $module);
        }

        return $paths;
    }
}
