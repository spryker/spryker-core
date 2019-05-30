<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\DataExpander;

use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig;

class PriceProductTransferPriceDimensionDataExpander implements PriceProductTransferDataExpanderInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig
     */
    protected $priceProductScheduleConfig;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig $priceProductScheduleConfig
     */
    public function __construct(
        PriceProductScheduleConfig $priceProductScheduleConfig
    ) {
        $this->priceProductScheduleConfig = $priceProductScheduleConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function expand(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        $priceProductDimensionTransfer = $this->getDefaultPriceProductDimension();

        return $priceProductTransfer
            ->setPriceDimension($priceProductDimensionTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    protected function getDefaultPriceProductDimension(): PriceProductDimensionTransfer
    {
        return (new PriceProductDimensionTransfer())
            ->setType($this->priceProductScheduleConfig->getPriceDimensionDefault());
    }
}
