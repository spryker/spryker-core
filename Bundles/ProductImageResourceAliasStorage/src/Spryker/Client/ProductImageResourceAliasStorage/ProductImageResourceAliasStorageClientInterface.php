<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImageResourceAliasStorage;

use Generated\Shared\Transfer\ProductAbstractImageStorageTransfer;
use Generated\Shared\Transfer\ProductConcreteImageStorageTransfer;

interface ProductImageResourceAliasStorageClientInterface
{
    /**
     * Specification:
     * - Retrieves a current store specific ProductAbstractImage resource from storage.
     *
     * @api
     *
     * @param string $sku
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractImageStorageTransfer|null
     */
    public function findProductImageAbstractStorageTransfer(string $sku, string $localeName): ?ProductAbstractImageStorageTransfer;

    /**
     * Specification:
     * - Retrieves a current store specific ProductConcreteImage resource from storage.
     *
     * @api
     *
     * @param string $sku
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductConcreteImageStorageTransfer|null
     */
    public function findProductImageConcreteStorageTransfer(string $sku, string $localeName): ?ProductConcreteImageStorageTransfer;
}
