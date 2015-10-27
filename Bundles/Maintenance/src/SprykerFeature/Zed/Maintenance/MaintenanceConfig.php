<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class MaintenanceConfig extends AbstractBundleConfig
{

    public function getBundleDirectory()
    {
        return APPLICATION_VENDOR_DIR
            . DIRECTORY_SEPARATOR . 'spryker'
            . DIRECTORY_SEPARATOR . 'spryker'
            . DIRECTORY_SEPARATOR . 'Bundles'
            . DIRECTORY_SEPARATOR
        ;
    }

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
        return APPLICATION_ROOT_DIR;
    }

    /**
     * @return string
     */
    public function getPathToSpryker()
    {
        return APPLICATION_VENDOR_DIR . DIRECTORY_SEPARATOR . 'spryker';
    }

    /**
     * @return string
     */
    public function getPathToFossFile()
    {
        return APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'FOSS.md';
    }

}
