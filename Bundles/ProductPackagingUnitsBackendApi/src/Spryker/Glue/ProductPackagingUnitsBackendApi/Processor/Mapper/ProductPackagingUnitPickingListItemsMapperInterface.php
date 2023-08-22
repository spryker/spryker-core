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
     * @param array<string, \Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer> $pickingListItemsBackendApiAttributesTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer>
     */
    public function mapPickingListItemTransfersToPickingListItemsBackendApiAttributesTransfers(
        array $pickingListItemTransfers,
        array $pickingListItemsBackendApiAttributesTransfers
    ): array;
}
