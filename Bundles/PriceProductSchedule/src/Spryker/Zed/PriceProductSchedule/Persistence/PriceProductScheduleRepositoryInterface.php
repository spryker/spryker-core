<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Persistence;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule;

interface PriceProductScheduleRepositoryInterface
{
    /**
     * @param int $idPriceProductSchedule
     *
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule|null
     */
    public function findByIdPriceProductSchedule(int $idPriceProductSchedule): ?SpyPriceProductSchedule;

    /**
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule[]
     */
    public function findPriceProductSchedulesToDisable(): array;

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule[]
     */
    public function findSimilarPriceProductSchedulesToDisable(PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): array;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule[]
     */
    public function findPriceProductSchedulesToEnableByStore(StoreTransfer $storeTransfer): array;
}
