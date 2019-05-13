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
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_DIMENSION_DEFAULT
     */
    public const PRICE_DIMENSION_DEFAULT = 'PRICE_DIMENSION_DEFAULT';

    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_TYPE_DEFAULT
     */
    public const PRICE_TYPE_DEFAULT = 'DEFAULT';

    public const PRICE_TYPE_ORIGINAL = 'ORIGINAL';

    protected const APPLY_BATCH_SIZE = 1000;

    /**
     * @return array
     */
    public function getFallbackPriceTypeList(): array
    {
        return [
            static::PRICE_TYPE_DEFAULT => static::PRICE_TYPE_ORIGINAL,
        ];
    }

    /**
     * @return int
     */
    public function getApplyBatchSize(): int
    {
        return static::APPLY_BATCH_SIZE;
    }

    /**
     * @return string
     */
    public function getPriceDimensionDefault(): string
    {
        return static::PRICE_DIMENSION_DEFAULT;
    }
}
