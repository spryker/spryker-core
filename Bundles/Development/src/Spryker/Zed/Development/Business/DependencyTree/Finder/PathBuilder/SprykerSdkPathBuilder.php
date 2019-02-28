<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\Finder\PathBuilder;

class SprykerSdkPathBuilder extends AbstractPathBuilder implements PathBuilderInterface
{
    protected const ORGANIZATION = 'SprykerSdk';

    protected const LOOKUP_NAMESPACES = [
        'src' => 'SprykerSdk',
        'tests' => 'SprykerSdkTest',
    ];

    /**
     * @param string $module
     *
     * @return array
     */
    public function buildPaths(string $module): array
    {
        $filteredModule = $this->filterModule($module);

        $paths = [];
        $basePath = rtrim($this->config->getPathToInternalNamespace(static::ORGANIZATION), '/');
        foreach ($this->config->getApplications() as $application) {
            foreach (static::LOOKUP_NAMESPACES as $namespace) {
                $paths[] = $this->getPath([$basePath, $filteredModule, $namespace, $application, $module]);
            }
        }

        return $paths;
    }
}
