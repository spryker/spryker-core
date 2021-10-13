<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductAlternativeStorageFacadeInterface
{
    /**
     * Specification:
     * - Publishes alternatives for given products.
     *
     * @api
     *
     * @param array<int> $idProduct
     *
     * @return void
     */
    public function publishAlternative(array $idProduct): void;

    /**
     * Specification:
     *  - Publishes replacements for abstract product
     *
     * @api
     *
     * @param array<int> $productIds
     *
     * @return void
     */
    public function publishAbstractReplacements(array $productIds): void;

    /**
     * Specification:
     *  - Publishes replacements for concrete product
     *
     * @api
     *
     * @param array<int> $productIds
     *
     * @return void
     */
    public function publishConcreteReplacements(array $productIds): void;

    /**
     * Specification:
     * - Returns an array of SynchronizationDataTransfer filtered by provided productAlternativeStorageIds.
     * - Uses FilterTransfer for pagination.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productAlternativeStorageIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationDataTransfersByFilterAndProductAlternativeStorageIds(
        FilterTransfer $filterTransfer,
        array $productAlternativeStorageIds = []
    ): array;

    /**
     * Specification:
     * - Returns an array of SynchronizationDataTransfer filtered by provided productReplacementForStorageIds.
     * - Uses FilterTransfer for pagination
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productReplacementForStorageIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationDataTransfersByFilterAndProductReplacementForStorageIds(
        FilterTransfer $filterTransfer,
        array $productReplacementForStorageIds = []
    ): array;
}
