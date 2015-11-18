<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Transfer;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

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
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/*/Shared/*/Transfer/',
//            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/CustomerCheckoutConnector/src/*/Shared/*/Transfer/',
        ];

        $applicationTransferGlobPattern = APPLICATION_SOURCE_DIR . '/*/Shared/*/Transfer/';
        if (glob($applicationTransferGlobPattern)) {
            $directories[] = $applicationTransferGlobPattern;
        }

        return $directories;
    }

}
