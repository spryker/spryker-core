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
     * @return string
     */
    public function getGeneratedTargetDirectory()
    {
        return APPLICATION_SOURCE_DIR . '/Generated/Shared/';
    }

    /**
     * @return array
     */
    public function getSourceDirectories()
    {
        $directories = [
            APPLICATION_SPRYKER_ROOT . '/*/src/*/Shared/*/Transfer/',
        ];

        $applicationTransferGlobPattern = APPLICATION_SOURCE_DIR . '/*/Shared/*/Transfer/';
        if (glob($applicationTransferGlobPattern)) {
            $directories[] = $applicationTransferGlobPattern;
        }

        return $directories;
    }

}
