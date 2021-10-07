<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Business\Model;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductQuantityReaderInterface
{
    /**
     * @param array<string> $productSkus
     *
     * @return array<\Generated\Shared\Transfer\ProductQuantityTransfer>
     */
    public function findProductQuantityTransfersByProductSku(array $productSkus): array;

    /**
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\ProductQuantityTransfer>
     */
    public function findProductQuantityTransfersByProductIds(array $productIds): array;

    /**
     * @return array<\Generated\Shared\Transfer\ProductQuantityTransfer>
     */
    public function findProductQuantityTransfers(): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductQuantityTransfer>
     */
    public function findFilteredProductQuantityTransfers(FilterTransfer $filterTransfer): array;
}
