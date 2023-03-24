<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingListExtension\Dependency\Plugin;

use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListOrderItemGroupTransfer;

/**
 * Provides split capabilities for picking lists before persistence.
 */
interface PickingListGeneratorStrategyPluginInterface
{
    /**
     * Specification:
     * - Returns true if the availability strategy is applicable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PickingListOrderItemGroupTransfer $pickingListOrderItemGroupTransfer
     *
     * @return bool
     */
    public function isApplicable(PickingListOrderItemGroupTransfer $pickingListOrderItemGroupTransfer): bool;

    /**
     * Specification:
     * - Generates the picking list collection by strategy business rules.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PickingListOrderItemGroupTransfer $pickingListOrderItemGroupTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function generatePickingLists(PickingListOrderItemGroupTransfer $pickingListOrderItemGroupTransfer): PickingListCollectionTransfer;
}
