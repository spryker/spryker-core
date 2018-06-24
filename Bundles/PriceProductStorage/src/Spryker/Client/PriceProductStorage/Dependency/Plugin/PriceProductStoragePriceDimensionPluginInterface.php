<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Dependency\Plugin;

use Generated\Shared\Transfer\PriceProductStorageTransfer;

interface PriceProductStoragePriceDimensionPluginInterface
{
    /**
     * Specification:
     *  - Returns prices data from Storage for concrete product.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findProductConcretePrices(int $idProductConcrete): ?PriceProductStorageTransfer;

    /**
     * * Specification:
     *  - Returns prices data from Storage for abstract product.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findProductAbstractPrices(int $idProductAbstract): ?PriceProductStorageTransfer;

    /**
     * Specification:
     *  - Returns dimension name.
     *
     * @api
     *
     * @return string
     */
    public function getDimensionName(): string;
}
