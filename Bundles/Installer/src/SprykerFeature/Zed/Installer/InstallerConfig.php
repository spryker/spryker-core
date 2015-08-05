<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Installer;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

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
        // Find flies in Core bundles
        return glob(
            APPLICATION_VENDOR_DIR
            . '/spryker/spryker/Bundles/*/src/SprykerFeature/*/*/Ressources/glossary.yml'
        );
    }
}
