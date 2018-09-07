<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImageStorage;

use Generated\Shared\Transfer\ProductAbstractImageStorageTransfer;
use Generated\Shared\Transfer\ProductConcreteImageStorageTransfer;

interface ProductImageStorageClientInterface
{
    /**
     * Specification:
     *  - Retrieves abstract product main data from storage.
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductAbstractImageStorageTransfer|null
     */
    public function findProductImageAbstractStorageTransfer(int $idProductAbstract, string $locale): ?ProductAbstractImageStorageTransfer;

    /**
     * Specification:
     *  - Retrieves concrete product main data from storage.
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductConcreteImageStorageTransfer|null
     */
    public function findProductImageConcreteStorageTransfer(int $idProductConcrete, string $locale): ?ProductConcreteImageStorageTransfer;
}
