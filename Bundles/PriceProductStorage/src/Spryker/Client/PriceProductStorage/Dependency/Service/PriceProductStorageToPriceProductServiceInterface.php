<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Dependency\Service;

use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceProductStorageToPriceProductServiceInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return string
     */
    public function buildPriceProductGroupKey(PriceProductTransfer $priceProductTransfer): string;

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $abstractPriceProductTransfers
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $concretePriceProductTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function mergeConcreteAndAbstractPrices(array $abstractPriceProductTransfers, array $concretePriceProductTransfers): array;
}
