<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\ProductAlternative;

use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeListTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;

interface ProductAlternativeReaderInterface
{
    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer
     */
    public function getProductAlternativesByIdProductConcrete(int $idProductConcrete): ProductAlternativeCollectionTransfer;

    /**
     * @param int $idProductAlternative
     *
     * @return null|\Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function getProductAlternativeByIdProductAlternative(int $idProductAlternative): ?ProductAlternativeTransfer;

    /**
     * @param int $idBaseProduct
     * @param int $idProductAbstract
     *
     * @return null|\Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function getProductAbstractAlternative(int $idBaseProduct, int $idProductAbstract): ?ProductAlternativeTransfer;

    /**
     * @param int $idBaseProduct
     * @param int $idProductConcrete
     *
     * @return null|\Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function getProductConcreteAlternative(int $idBaseProduct, int $idProductConcrete): ?ProductAlternativeTransfer;

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListTransfer
     */
    public function getProductAlternativeListByIdProductConcrete(int $idProductConcrete): ProductAlternativeListTransfer;
}
