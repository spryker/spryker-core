<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApiExtension\Dependency\Plugin;

/**
 * Implement this plugin if you want to map extra data from `PickingListItemTransfer` to `ApiPickingListItemsAttributesTransfer`.
 */
interface ApiPickingListItemsAttributesMapperPluginInterface
{
    /**
     * Specification:
     * - Maps product list item data from `PickingListItemTransfer` to `ApiPickingListItemsAttributesTransfer`.
     * - Array `$pickingListItemTransfers` should remain unchanged.
     * - Array keys of `$apiPickingListItemsAttributesTransfers` should remain unchanged.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemTransfers
     * @param array<string, \Generated\Shared\Transfer\ApiPickingListItemsAttributesTransfer> $apiPickingListItemsAttributesTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\ApiPickingListItemsAttributesTransfer>
     */
    public function mapPickingListItemTransfersToApiPickingListItemsAttributesTransfers(
        array $pickingListItemTransfers,
        array $apiPickingListItemsAttributesTransfers
    ): array;
}
