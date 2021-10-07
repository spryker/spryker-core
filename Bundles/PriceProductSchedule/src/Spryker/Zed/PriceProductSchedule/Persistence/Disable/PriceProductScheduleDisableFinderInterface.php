<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Persistence\Disable;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;

interface PriceProductScheduleDisableFinderInterface
{
    /**
     * @module Product
     * @module PriceProduct
     * @module Currency
     *
     * @return array<\Generated\Shared\Transfer\PriceProductScheduleTransfer>
     */
    public function findPriceProductSchedulesToDisable(): array;

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return bool
     */
    public function isScheduledPriceForSwitchExists(PriceProductScheduleTransfer $priceProductScheduleTransfer): bool;

    /**
     * @module Product
     * @module PriceProduct
     * @module Currency
     *
     * @param int $idProductAbstract
     *
     * @return array<\Generated\Shared\Transfer\PriceProductScheduleTransfer>
     */
    public function findPriceProductSchedulesToDisableByIdProductAbstract(int $idProductAbstract): array;

    /**
     * @module Product
     * @module PriceProduct
     * @module Currency
     *
     * @param int $idProductConcrete
     *
     * @return array<\Generated\Shared\Transfer\PriceProductScheduleTransfer>
     */
    public function findPriceProductSchedulesToDisableByIdProductConcrete(int $idProductConcrete): array;

    /**
     * @module Product
     * @module PriceProduct
     * @module Currency
     *
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductScheduleTransfer>
     */
    public function findSimilarPriceProductSchedulesToDisable(PriceProductScheduleTransfer $priceProductScheduleTransfer): array;
}
