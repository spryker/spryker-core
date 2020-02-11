<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Persistence\Finder;

use Generated\Shared\Transfer\PriceProductScheduleListTransfer;

interface PriceProductScheduleListFinderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer|null
     */
    public function findPriceProductScheduleListById(PriceProductScheduleListTransfer $priceProductScheduleListTransfer): ?PriceProductScheduleListTransfer;

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer|null
     */
    public function findPriceProductScheduleListByName(string $name): ?PriceProductScheduleListTransfer;
}
