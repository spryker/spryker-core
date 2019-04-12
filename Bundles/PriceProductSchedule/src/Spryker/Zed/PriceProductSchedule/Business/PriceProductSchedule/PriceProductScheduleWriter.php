<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface;

class PriceProductScheduleWriter implements PriceProductScheduleWriterInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface
     */
    protected $priceProductScheduleEntityManager;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager
     */
    public function __construct(
        PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager
    ) {
        $this->priceProductScheduleEntityManager = $priceProductScheduleEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function savePriceProductSchedule(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): PriceProductScheduleTransfer {
        $priceProductScheduleTransfer->requireIdPriceProductSchedule();

        return $this->priceProductScheduleEntityManager
            ->savePriceProductSchedule($priceProductScheduleTransfer);
    }
}
