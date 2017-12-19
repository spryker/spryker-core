<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceStorage;

use Spryker\Shared\Price\PriceConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class PriceStorageConfig extends AbstractBundleConfig
{

    public const PRICE_TYPE_DEFAULT = 'DEFAULT';

    /**
     * @return string
     */
    public function getDefaultPriceType()
    {
        return $this->get(PriceConstants::DEFAULT_PRICE_TYPE, static::PRICE_TYPE_DEFAULT);
    }
}
