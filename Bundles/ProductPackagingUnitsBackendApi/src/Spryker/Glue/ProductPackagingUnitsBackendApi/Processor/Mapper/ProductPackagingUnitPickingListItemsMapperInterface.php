<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPackagingUnitsBackendApi\Processor\Mapper;

interface ProductPackagingUnitPickingListItemsMapperInterface
{
    /**
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
