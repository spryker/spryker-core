<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApiExtension\Dependency\Plugin;

/**
 * Implement this plugin if you want to map extra data from `PickingListItemTransfer` to `PickingListItemsBackendApiAttributesTransfer`.
 */
interface PickingListItemsBackendApiAttributesMapperPluginInterface
{
    /**
     * Specification:
     * - Maps product list item data from `PickingListItemTransfer` to `PickingListItemsBackendApiAttributesTransfer`.
     * - Array `$pickingListItemTransfers` should remain unchanged.
     * - Array keys of `$pickingListItemsBackendApiAttributesTransfers` should remain unchanged.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemTransfers
     * @param array<string, \Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer> $pickingListItemsBackendApiAttributesTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer>
     */
    public function mapPickingListItemTransfersToPickingListItemsBackendApiAttributesTransfers(
        array $pickingListItemTransfers,
        array $pickingListItemsBackendApiAttributesTransfers
    ): array;
}
