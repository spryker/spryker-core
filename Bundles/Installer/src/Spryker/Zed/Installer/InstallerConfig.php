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
    public function getGlossaryFilePaths()
    {
        return glob(
            APPLICATION_SPRYKER_ROOT . '/*/src/Spryker/*/*/Resources/glossary.yml'
        );
    }

}
