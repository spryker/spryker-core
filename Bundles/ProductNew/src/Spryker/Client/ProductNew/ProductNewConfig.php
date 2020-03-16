<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductNew;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\ProductNew\ProductNewConfig as SprykerSharedProductNewConfig;

class ProductNewConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getLabelNewName()
    {
        return SprykerSharedProductNewConfig::DEFAULT_LABEL_NAME;
    }
}
