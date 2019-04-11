<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleList;

interface PriceProductScheduleListMapperInterface
{
    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleList $priceProductScheduleListEntity
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer
     */
    public function mapPriceProductScheduleListEntityToPriceProductScheduleListTransfer(
        SpyPriceProductScheduleList $priceProductScheduleListEntity,
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): PriceProductScheduleListTransfer;
}
