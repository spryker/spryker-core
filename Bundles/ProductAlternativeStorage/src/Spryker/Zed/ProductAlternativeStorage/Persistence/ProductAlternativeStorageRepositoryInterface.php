<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Persistence;

use Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer;

interface ProductAlternativeStorageRepositoryInterface
{
    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer
     */
    public function findProductAlternativeStorageEntity($idProduct): SpyProductAlternativeStorageEntityTransfer;

    /**
     * @param $idProduct
     *
     * @return string|null
     */
    public function findProductSkuById($idProduct);

    /**
     * @param $idProduct
     */
    public function findAbstractAlternativesIdsByConcreteProductId($idProduct);

    /**
     * @param $idProduct
     */
    public function findConcreteAlternativesIdsByConcreteProductId($idProduct);
}
