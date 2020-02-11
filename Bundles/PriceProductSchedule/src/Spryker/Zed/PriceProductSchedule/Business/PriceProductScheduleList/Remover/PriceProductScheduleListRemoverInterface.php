<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList\Remover;

use Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer;

interface PriceProductScheduleListRemoverInterface
{
    /**
     * @param int $idPriceProductScheduleList
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer
     */
    public function removePriceProductScheduleList(int $idPriceProductScheduleList): PriceProductScheduleListResponseTransfer;
}
