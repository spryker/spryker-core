<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorageExtension\Dependency\Plugin;

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
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductConcretePrices(int $idProductConcrete): array;

    /**
     * Specification:
     *  - Returns prices data from Storage for abstract product.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductAbstractPrices(int $idProductAbstract): array;

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
