<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\ItemValidationTransfer;
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
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
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
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
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
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
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
     * - Requires ItemTransfer inside ItemValidationTransfer.
     * - Returns not modified ItemValidationTransfer if ItemValidationTransfer.Item.id is missing.
     * - Gets ItemTransfer from the ItemValidationTransfer.
     * - Requires quantity and idProductAbstract in ItemTransfer if ItemTransfer.id is present.
     * - Creates PriceProductFilterTransfer and fill it with the quantity, id and idProductAbstract from the ItemTransfer.
     * - Tries to find product price using the PriceProductStorageClient::resolveCurrentProductPriceTransfer().
     * - Adds error message if price not found. Otherwise returns not modified ItemValidationTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemValidationTransfer $itemValidationTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationTransfer
     */
    public function validateItemProductPrice(ItemValidationTransfer $itemValidationTransfer): ItemValidationTransfer;
}
