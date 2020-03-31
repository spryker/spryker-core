<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Mapper;

use Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer;
use Generated\Shared\Transfer\RestDateScheduleTransfer;
use Generated\Shared\Transfer\RestMerchantOpeningHoursAttributesTransfer;
use Generated\Shared\Transfer\RestWeekdayScheduleTransfer;

class MerchantOpeningHoursMapper implements MerchantOpeningHoursMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer
     * @param \Generated\Shared\Transfer\RestMerchantOpeningHoursAttributesTransfer $restMerchantOpeningHoursAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestMerchantOpeningHoursAttributesTransfer
     */
    public function mapMerchantOpeningHoursStorageTransferToRestMerchantOpeningHoursAttributesTransfer(
        MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer,
        RestMerchantOpeningHoursAttributesTransfer $restMerchantOpeningHoursAttributesTransfer
    ): RestMerchantOpeningHoursAttributesTransfer {
        foreach ($merchantOpeningHoursStorageTransfer->getDateSchedule() as $dateScheduleTransfer) {
            $restDateScheduleTransfer = (new RestDateScheduleTransfer())->fromArray($dateScheduleTransfer->toArray(), true);
            $restMerchantOpeningHoursAttributesTransfer->addDateSchedule($restDateScheduleTransfer);
        }

        foreach ($merchantOpeningHoursStorageTransfer->getWeekdaySchedule() as $weekdayScheduleTransfer) {
            $restWeekdayScheduleTransfer = (new RestWeekdayScheduleTransfer())->fromArray($weekdayScheduleTransfer->toArray(), true);
            $restMerchantOpeningHoursAttributesTransfer->addWeekdaySchedule($restWeekdayScheduleTransfer);
        }

        return $restMerchantOpeningHoursAttributesTransfer;
    }
}
