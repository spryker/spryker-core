<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MaintenanceConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getBundleDirectory()
    {
        return APPLICATION_SPRYKER_ROOT . DIRECTORY_SEPARATOR;
    }

    /**
     * @return array
     */
    public function getExcludedDirectoriesForDependencies()
    {
        return ['Persistence/Propel/Base', 'Persistence/Propel/Map'];
    }

    /**
     * @return string
     */
    public function getPathToComposerLock()
    {
        return APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'composer.lock';
    }

    /**
     * @return string
     */
    public function getPathToRoot()
    {
        return APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    public function getPathToFossFile()
    {
        return APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'FOSS.md';
    }

    /**
     * @return string
     */
    public function getPathToJsonDependencyTree()
    {
        $pathParts = [
            APPLICATION_ROOT_DIR,
            'data',
            'dependencyTree.json',
        ];

        return implode(DIRECTORY_SEPARATOR, $pathParts);
    }

    /**
     * @return string
     */
    public function getPathToBundleConfig()
    {
        return APPLICATION_SPRYKER_ROOT . '/../bundle_config.json';
    }

}
