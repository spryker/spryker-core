<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductQuantityStorageFacadeInterface
{
    /**
     * Specification:
     * - Publishes product quantity changes for the given product IDs.
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishProductQuantity(array $productIds): void;

    /**
     * Specification:
     * - Retrieves all product quantity transfers.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer[]
     */
    public function findProductQuantityTransfers(): array;

    /**
     * Specification:
     * - Retrieves product quantity transfers by product ids.
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer[]
     */
    public function findProductQuantityByProductIdsTransfers(array $productIds): array;

    /**
     * Specification:
     * - Retrieves product quantity transfers according to given offset and limit.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractTransfer[]
     */
    public function findFilteredProductQuantityTransfers(FilterTransfer $filterTransfer): array;
}
