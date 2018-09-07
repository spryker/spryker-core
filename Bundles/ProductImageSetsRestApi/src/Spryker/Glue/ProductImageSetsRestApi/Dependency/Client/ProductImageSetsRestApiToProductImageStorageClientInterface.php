<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsRestApi\Dependency\Client;

use Generated\Shared\Transfer\ProductAbstractImageStorageTransfer;
use Generated\Shared\Transfer\ProductConcreteImageStorageTransfer;

interface ProductImageSetsRestApiToProductImageStorageClientInterface
{
    /**
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductAbstractImageStorageTransfer|null
     */
    public function findProductImageAbstractStorageTransfer(int $idProductAbstract, string $locale): ?ProductAbstractImageStorageTransfer;

    /**
     * @param int $idProductConcrete
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductConcreteImageStorageTransfer|null
     */
    public function findProductImageConcreteStorageTransfer(int $idProductConcrete, string $locale): ?ProductConcreteImageStorageTransfer;
}
