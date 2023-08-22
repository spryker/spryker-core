<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;

interface PickingListItemMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     * @param \Generated\Shared\Transfer\PickingListItemTransfer $pickingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListItemTransfer
     */
    public function mapPickingListItemGlueResourceTransferToPickingListItemTransfer(
        GlueResourceTransfer $glueResourceTransfer,
        PickingListItemTransfer $pickingListItemTransfer
    ): PickingListItemTransfer;

    /**
     * @param list<\Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemTransfers
     * @param \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRelationshipTransfer
     */
    public function mapPickingListItemTransfersToGlueRelationshipTransfer(
        array $pickingListItemTransfers,
        GlueRelationshipTransfer $glueRelationshipTransfer
    ): GlueRelationshipTransfer;

    /**
     * @param \Generated\Shared\Transfer\PickingListItemTransfer $pickingListItemTransfer
     * @param \Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer $pickingListItemsBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer
     */
    public function mapPickingListItemTransferToPickingListItemsBackendApiAttributesTransfer(
        PickingListItemTransfer $pickingListItemTransfer,
        PickingListItemsBackendApiAttributesTransfer $pickingListItemsBackendApiAttributesTransfer
    ): PickingListItemsBackendApiAttributesTransfer;
}
