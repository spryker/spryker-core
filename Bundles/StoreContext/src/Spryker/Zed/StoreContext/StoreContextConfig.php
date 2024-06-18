<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class StoreContextConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns a list of allowed store context applications.
     *
     * @api
     *
     * @return array<string>
     */
    public function getStoreContextApplications(): array
    {
        return [
            'ZED',
            'YVES',
            'MERCHANT_PORTAL',
            'GLUE',
            'GLUE_STOREFRONT',
            'GLUE_BACKEND',
        ];
    }
}
