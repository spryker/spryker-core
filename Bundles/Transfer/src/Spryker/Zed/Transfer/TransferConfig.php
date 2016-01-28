<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Transfer;

use Spryker\Shared\Transfer\TransferConstants;
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
            $this->get(TransferConstants::SPRYKER_BUNDLES_ROOT) . '/*/src/*/Shared/*/Transfer/',
        ];

        $applicationTransferGlobPattern = APPLICATION_SOURCE_DIR . '/*/Shared/*/Transfer/';
        if (glob($applicationTransferGlobPattern)) {
            $directories[] = $applicationTransferGlobPattern;
        }

        return $directories;
    }

}
