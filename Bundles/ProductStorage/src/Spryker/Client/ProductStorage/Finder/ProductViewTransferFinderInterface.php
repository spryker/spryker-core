<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Finder;

use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductViewTransferFinderInterface
{
    /**
     * @param int $idProduct
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    public function findProductViewTransfer(int $idProduct, string $localeName, array $selectedAttributes = []): ?ProductViewTransfer;

    /**
     * @param int[] $productIds
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function getProductViewTransfers(array $productIds, string $localeName, array $selectedAttributes = []): array;
}
