<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\ProductAlternative;

use Generated\Shared\Transfer\ProductAlternativeListTransfer;

interface ProductAlternativeReaderInterface
{
    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListTransfer
     */
    public function getProductAlternativeListByIdProductConcrete(int $idProductConcrete): ProductAlternativeListTransfer;

    /**
     * @param array<int> $productIds
     *
     * @return bool
     */
    public function doAllConcreteProductsHaveAlternatives(array $productIds): bool;

    /**
     * @param int $idProduct
     *
     * @return bool
     */
    public function isAlternativeProductApplicable(int $idProduct): bool;

    /**
     * @return array<int>
     */
    public function findProductAbstractIdsWhichConcreteHasAlternative(): array;
}
