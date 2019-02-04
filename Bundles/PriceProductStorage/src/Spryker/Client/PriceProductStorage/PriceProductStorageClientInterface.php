<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ItemValidationResponseTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;

interface PriceProductStorageClientInterface
{
    /**
     * Specification:
     *  - Returns abstract product prices from Storage.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getPriceProductAbstractTransfers(int $idProductAbstract): array;

    /**
     * Specification:
     *  - Returns concrete product prices from Storage.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getPriceProductConcreteTransfers(int $idProductConcrete): array;

    /**
     * Specification:
     *  - Returns a resolved concrete product price from storage.
     *  - Returns product concrete price data if it exists.
     *  - Returns product abstract price data otherwise.
     *  - Merges concrete prices to abstract prices when both available.
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getResolvedPriceProductConcreteTransfers(int $idProductConcrete, int $idProductAbstract): array;

    /**
     * Specification:
     * - Returns product price based on the provided PriceProductFilterTransfer.
     * - Uses product concrete prices when available.
     * - Uses product abstract prices as fall back.
     * - Merges concrete prices to abstract prices when both available.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function getResolvedCurrentProductPriceTransfer(PriceProductFilterTransfer $priceProductFilterTransfer): CurrentProductPriceTransfer;

    /**
     * Specification:
     * - Validates ItemTransfer with the product price validation.
     * - Returns ItemValidationResponseTransfer with error or warning messages.
     * - In case any fields need to be updated ItemValidationResponseTransfer contains ItemTransfer with 'recommended values transfer' inside.
     * - Returns empty ItemValidationResponseTransfer when no validation errors or warnings.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationResponseTransfer
     */
    public function validateItemTransfer(ItemTransfer $itemTransfer): ItemValidationResponseTransfer;
}
