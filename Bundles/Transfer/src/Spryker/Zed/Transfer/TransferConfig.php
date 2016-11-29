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
        $directories = [
            Config::get(ApplicationConstants::APPLICATION_SPRYKER_ROOT) . '/*/src/*/Shared/*/Transfer/',
        ];

        $additionalGlobPatterns = $this->getAdditionalSourceDirectoryGlobPatterns();
        array_unshift($additionalGlobPatterns, $this->getApplicationSourceDirectoryGlobPattern());

        foreach ($additionalGlobPatterns as $globPattern) {
            if (glob($globPattern, GLOB_ONLYDIR)) {
                $directories[] = $globPattern;
            }
        }

        return $directories;
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

    /**
     * @return string
     */
    protected function getApplicationSourceDirectoryGlobPattern()
    {
        return APPLICATION_SOURCE_DIR . '/*/Shared/*/Transfer/';
    }

}
