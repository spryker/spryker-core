<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Development;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class DevelopmentConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getBundleDirectory()
    {
        return APPLICATION_VENDOR_DIR
            . DIRECTORY_SEPARATOR . 'spryker'
            . DIRECTORY_SEPARATOR . 'spryker'
            . DIRECTORY_SEPARATOR . 'Bundles'
            . DIRECTORY_SEPARATOR;
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
    public function getPathToSpryker()
    {
        return APPLICATION_VENDOR_DIR . DIRECTORY_SEPARATOR . 'spryker' . DIRECTORY_SEPARATOR;
    }

}
