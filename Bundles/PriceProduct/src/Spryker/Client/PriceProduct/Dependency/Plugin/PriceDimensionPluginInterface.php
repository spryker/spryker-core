<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProduct\Dependency\Plugin;

use Generated\Shared\Transfer\PriceProductStorageTransfer;

interface PriceDimensionPluginInterface
{
    /**
     * Specification:
     *  - Returns prices from Storage for concrete product.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findProductConcretePrice(int $idProductConcrete): ?PriceProductStorageTransfer;

    /**
     * * Specification:
     *  - Returns prices from Storage for abstract product.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findProductAbstractPrice(int $idProductAbstract): ?PriceProductStorageTransfer;
}
