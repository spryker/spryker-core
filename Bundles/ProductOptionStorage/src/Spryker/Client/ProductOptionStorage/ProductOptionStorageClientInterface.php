<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOptionStorage;

interface ProductOptionStorageClientInterface
{
    /**
     * Specification:
     * - Return ProductOption data from storage for the given idProductAbstract
     *
     * @api
     *
     * @param int $idAbstractProduct
     * @param int $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer
     */
    public function getProductOptions($idAbstractProduct, $localeName);

    /**
     * Specification:
     * - Returns ProductOption data from storage for the given idProductAbstract
     * - Returns ProductOption only for CurrentStore
     *
     * @api
     *
     * @param int $idAbstractProduct
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer
     */
    public function getProductOptionsForCurrentStore($idAbstractProduct);
}
