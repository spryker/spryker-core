<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductQuantityRepositoryInterface
{
    /**
     * @api
     *
     * @param string[] $productSkus
     *
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer[]
     */
    public function findProductQuantityTransfersByProductSku(array $productSkus): array;

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer[]
     */
    public function findProductQuantityTransfersByProductIds(array $productIds): array;

    /**
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer[]
     */
    public function findProductQuantityTransfers(): array;

    /**
     * @param int[] $productIds
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer[]
     */
    public function findProductQuantityTransfersByProductIdsFilteredByOffsetAndLimit(array $productIds, FilterTransfer $filterTransfer): array;
}
