<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Testify;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class TestifyConfig extends AbstractBundleConfig
{
    /**
     * @return string[]
     */
    public function getOutputDirectoriesForCleanup(): array
    {
        $directories = [
            APPLICATION_ROOT_DIR . '/tests/_output/',
            APPLICATION_ROOT_DIR . '/tests/PyzTest/*/*/_output/',
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/tests/_output/',
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/tests/SprykerTest/*/*/_output/',
            APPLICATION_VENDOR_DIR . '/spryker/spryker-shop/Bundles/*/tests/SprykerTest/*/*/_output/',
        ];

        $directories = array_filter($directories, 'glob');

        return $directories;
    }
}
