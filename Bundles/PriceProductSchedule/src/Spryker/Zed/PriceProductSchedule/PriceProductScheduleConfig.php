<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class PriceProductScheduleConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_TYPE_DEFAULT
     */
    public const PRICE_TYPE_DEFAULT = 'DEFAULT';

    public const PRICE_TYPE_ORIGINAL = 'ORIGINAL';

    /**
     * @return array
     */
    public function getFallbackPriceTypeList(): array
    {
        return [
            static::PRICE_TYPE_DEFAULT => static::PRICE_TYPE_ORIGINAL,
        ];
    }
}
