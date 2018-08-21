<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductResourceAliasStorage;

use Generated\Shared\Transfer\PriceProductStorageTransfer;

interface PriceProductResourceAliasStorageClientInterface
{
    /**
     * Specification:
     *  - Returns entity price product abstract by abstract product sku.
     *  - Returns null if price product abstract entity with given abstract product sku doesn't exists
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findPriceProductAbstractStorageTransfer(string $sku): ?PriceProductStorageTransfer;

    /**
     * Specification:
     *  - Returns entity price product concrete by concrete product sku.
     *  - Returns null if price product concrete entity with given concrete product sku doesn't exists
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findPriceProductConcreteStorageTransfer(string $sku): ?PriceProductStorageTransfer;
}
