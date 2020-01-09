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
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $idProduct
     *
     * @return void
     */
    public function publishAlternative(array $idProduct): void;

    /**
     * Specification:
     *  - Publish replacements for abstract product
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishAbstractReplacements(array $productIds): void;

    /**
     * Specification:
     *  - Publish replacements for concrete product
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishConcreteReplacements(array $productIds): void;

    /**
     * Specification:
     * - Queries product alternative storage entities for the given productAlternativeStorageIds filtered by provided FilterTransfer.
     * - Returns a collection of synchronization data transfers mapped from received storage entities.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productAlternativeStorageIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getSynchronizationDataTransfersByFilterAndProductAlternativeStorageIds(
        FilterTransfer $filterTransfer,
        array $productAlternativeStorageIds = []
    ): array;

    /**
     * Specification:
     * - Queries product replacement for storage entities for the given productReplacementForStorageIds filtered by provided FilterTransfer.
     * - Returns a collection of synchronization data transfers mapped from received storage entities.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productReplacementForStorageIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getSynchronizationDataTransfersByFilterAndProductReplacementForStorageIds(
        FilterTransfer $filterTransfer,
        array $productReplacementForStorageIds = []
    ): array;
}
