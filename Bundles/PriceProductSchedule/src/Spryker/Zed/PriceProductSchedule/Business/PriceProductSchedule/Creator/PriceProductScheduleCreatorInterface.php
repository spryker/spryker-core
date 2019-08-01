<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2019-08-01
 * Time: 12:27
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Creator;

use Generated\Shared\Transfer\PriceProductScheduleResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;

interface PriceProductScheduleCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleResponseTransfer
     */
    public function createAndApplyPriceProductSchedule(PriceProductScheduleTransfer $priceProductScheduleTransfer): PriceProductScheduleResponseTransfer;
}
