<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class TransferConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getClassTargetDirectory()
    {
        return APPLICATION_SOURCE_DIR . '/Generated/Shared/Transfer/';
    }

    /**
     * @return string[]
     */
    public function getSourceDirectories()
    {
        $globPatterns = $this->getCoreSourceDirectoryGlobPatterns();
        $globPatterns[] = $this->getApplicationSourceDirectoryGlobPattern();

        $globPatterns = array_merge($globPatterns, $this->getAdditionalSourceDirectoryGlobPatterns());

        return $globPatterns;
    }

    /**
     * @deprecated please use TransferConfig::getCoreSourceDirectoryGlobPatterns() instead
     *
     * @return string
     */
    protected function getSprykerCoreSourceDirectoryGlobPattern()
    {
        return APPLICATION_VENDOR_DIR . '/*/*/src/*/Shared/*/Transfer/';
    }

    /**
     * @return string[]
     */
    protected function getCoreSourceDirectoryGlobPatterns()
    {
        /**
         * This is added for keeping the BC and needs to be
         * replaced with the actual return of
         * getSprykerCoreSourceDirectoryGlobPattern() method
         */
        return [
            $this->getSprykerCoreSourceDirectoryGlobPattern(),
        ];
    }

    /**
     * @return string
     */
    protected function getApplicationSourceDirectoryGlobPattern()
    {
        return APPLICATION_SOURCE_DIR . '/*/Shared/*/Transfer/';
    }

    /**
     * @deprecated please use TransferConfig::getCoreSourceDirectoryGlobPatterns() instead
     *
     * This method can be used to extend the list of directories for transfer object
     * discovery in project implementations.
     *
     * @return string[]
     */
    protected function getAdditionalSourceDirectoryGlobPatterns()
    {
        return [];
    }

}
