<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Processor\Filter;

use Generated\Shared\Transfer\GlueResourceTransfer;

class PickingListItemResourceFilter implements PickingListItemResourceFilterInterface
{
    /**
     * @uses \Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig::RESOURCE_PICKING_LIST_ITEMS
     *
     * @var string
     */
    protected const RESOURCE_PICKING_LIST_ITEMS = 'picking-list-items';

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     *
     * @return list<\Generated\Shared\Transfer\GlueResourceTransfer>
     */
    public function filterPickingListItemResources(array $glueResourceTransfers): array
    {
        $pickingListItemsResourceTransfers = [];
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            if ($this->isApplicablePickingListItemsResource($glueResourceTransfer)) {
                $pickingListItemsResourceTransfers[] = $glueResourceTransfer;
            }
        }

        return $pickingListItemsResourceTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     *
     * @return bool
     */
    protected function isApplicablePickingListItemsResource(
        GlueResourceTransfer $glueResourceTransfer
    ): bool {
        return $glueResourceTransfer->getType() === static::RESOURCE_PICKING_LIST_ITEMS;
    }
}
