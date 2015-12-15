<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Installer;

use Spryker\Zed\Kernel\AbstractBundleConfig;

abstract class InstallerConfig extends AbstractBundleConfig
{

    /**
     * @return array
     */
    abstract public function getInstallerStack();

    /**
     * @return array
     */
    public function getDemoDataInstallerStack()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getGlossaryFilePaths()
    {
        // Find files in Core bundles
        return glob(
            APPLICATION_VENDOR_DIR
            . '/spryker/spryker/Bundles/*/src/Spryker/*/*/Ressources/glossary.yml'
        );
    }

}
