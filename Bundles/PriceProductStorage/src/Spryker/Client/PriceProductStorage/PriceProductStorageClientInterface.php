<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage;

use Generated\Shared\Transfer\PriceProductStorageTransfer;

interface PriceProductStorageClientInterface
{
    /**
     * Specification:
     * - Finds product price by product abstract id
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findPriceAbstractStorageTransfer(int $idProductAbstract): ?PriceProductStorageTransfer;

    /**
     * Specification:
     * - Finds product price by product abstract id
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findPriceConcreteStorageTransfer(int $idProductConcrete): ?PriceProductStorageTransfer;
}
