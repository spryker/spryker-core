<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingListExtension\Dependency\Plugin;

use Generated\Shared\Transfer\PickingListCollectionTransfer;

/**
 * Provides an ability to expand `PickingListCollectionTransfer` with additional data.
 *
 * Implement this plugin interface to expand `PickingListCollectionTransfer` after the fetching picking list data from the persistence.
 */
interface PickingListCollectionExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands `PickingListCollectionTransfer` with an additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function expand(PickingListCollectionTransfer $pickingListCollectionTransfer): PickingListCollectionTransfer;
}
