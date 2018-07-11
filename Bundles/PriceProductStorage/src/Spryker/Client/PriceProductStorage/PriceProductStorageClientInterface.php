<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage;

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
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]|null
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
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]|null
     */
    public function getPriceProductConcreteTransfers(int $idProductConcrete): array;
}
