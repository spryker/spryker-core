<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
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
     * @return array
     */
    public function getSourceDirectories()
    {
        $globPatterns = [
            $this->getSprykerCoreSourceDirectoryGlobPattern(),
            $this->getApplicationSourceDirectoryGlobPattern(),
        ];

        $globPatterns = array_merge($globPatterns, $this->getAdditionalSourceDirectoryGlobPatterns());

        return $globPatterns;
    }

    /**
     * @return string
     */
    protected function getSprykerCoreSourceDirectoryGlobPattern()
    {
        return Config::get(ApplicationConstants::APPLICATION_SPRYKER_ROOT) . '/*/src/*/Shared/*/Transfer/';
    }

    /**
     * @return string
     */
    protected function getApplicationSourceDirectoryGlobPattern()
    {
        return APPLICATION_SOURCE_DIR . '/*/Shared/*/Transfer/';
    }

    /**
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
