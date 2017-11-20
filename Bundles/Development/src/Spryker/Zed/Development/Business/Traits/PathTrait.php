<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Traits;

use Spryker\Zed\Development\DevelopmentConfig;

/**
 * @property \Spryker\Zed\Development\DevelopmentConfig $config
 */
trait PathTrait
{
    /**
     * @param string $module
     * @param string|null $pathSuffix
     *
     * @return string
     */
    protected function resolveProjectPath($module, $pathSuffix = null)
    {
        $projectNamespaces = $this->config->getProjectNamespaces();
        $namespaces = array_merge(DevelopmentConfig::APPLICATION_NAMESPACES, $projectNamespaces);
        $pathToRoot = $this->config->getPathToRoot();

        $paths = [];
        foreach ($namespaces as $namespace) {
            $path = $pathToRoot . 'src' . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR;

            foreach (DevelopmentConfig::APPLICATION_LAYERS as $layer) {
                $layerPath = $path . $layer . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR;
                if ($pathSuffix) {
                    $layerPath .= $pathSuffix;
                }

                if (!is_dir($layerPath)) {
                    continue;
                }

                $paths[] = $layerPath;
            }
        }

        return implode(' ', $paths);
    }
}
