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

}
