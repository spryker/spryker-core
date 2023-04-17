<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsUsersBackendResourceRelationship\Processor\Filter;

use Generated\Shared\Transfer\GlueResourceTransfer;

class PickingListResourceFilter implements PickingListResourceFilterInterface
{
    /**
     * @uses \Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig::RESOURCE_PICKING_LISTS
     *
     * @var string
     */
    protected const RESOURCE_PICKING_LISTS = 'picking-lists';

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     *
     * @return list<\Generated\Shared\Transfer\GlueResourceTransfer>
     */
    public function filterPickingListResources(array $glueResourceTransfers): array
    {
        $pickingListsResourceTransfers = [];
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            if ($this->isApplicablePickingListsResource($glueResourceTransfer)) {
                $pickingListsResourceTransfers[] = $glueResourceTransfer;
            }
        }

        return $pickingListsResourceTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     *
     * @return bool
     */
    protected function isApplicablePickingListsResource(
        GlueResourceTransfer $glueResourceTransfer
    ): bool {
        return $glueResourceTransfer->getType() === static::RESOURCE_PICKING_LISTS;
    }
}
