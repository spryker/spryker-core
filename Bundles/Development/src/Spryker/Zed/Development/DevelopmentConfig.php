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
        return APPLICATION_SPRYKER_ROOT . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    public function getPathToRoot()
    {
        return APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR;
    }

    /**
     * @deprecated use getBundleDirectory() to get the path to bundles
     *
     * @return string
     */
    public function getPathToSpryker()
    {
        return $this->getBundleDirectory();
    }

}
