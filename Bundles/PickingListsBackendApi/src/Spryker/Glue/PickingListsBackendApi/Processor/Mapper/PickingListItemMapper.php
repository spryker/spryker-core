<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiOrderItemsAttributesTransfer;
use Generated\Shared\Transfer\ApiPickingListItemsAttributesTransfer;
use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;
use Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig;

class PickingListItemMapper implements PickingListItemMapperInterface
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
    ): PickingListItemTransfer {
        $pickingListItemTransfer->setUuid($glueResourceTransfer->getId());

        /** @var \Generated\Shared\Transfer\ApiPickingListItemsAttributesTransfer|null $apiPickingListItemsAttributesTransfer */
        $apiPickingListItemsAttributesTransfer = $glueResourceTransfer->getAttributes();
        if (!$apiPickingListItemsAttributesTransfer) {
            return $pickingListItemTransfer;
        }

        $pickingListItemTransfer->setNumberOfPicked(
            $apiPickingListItemsAttributesTransfer->getNumberOfPicked(),
        )->setNumberOfNotPicked(
            $apiPickingListItemsAttributesTransfer->getNumberOfNotPicked(),
        );

        return $pickingListItemTransfer;
    }

    /**
     * @param list<\Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemTransfers
     * @param \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRelationshipTransfer
     */
    public function mapPickingListItemTransfersToGlueRelationshipTransfer(
        array $pickingListItemTransfers,
        GlueRelationshipTransfer $glueRelationshipTransfer
    ): GlueRelationshipTransfer {
        $apiPickingListItemsAttributesTransfers = [];
        foreach ($pickingListItemTransfers as $pickingListItemTransfer) {
            $apiPickingListItemsAttributesTransfers[$pickingListItemTransfer->getUuidOrFail()] = $this->mapPickingListItemTransferToApiPickingListItemsAttributesTransfer(
                $pickingListItemTransfer,
                new ApiPickingListItemsAttributesTransfer(),
            );
        }

        foreach ($apiPickingListItemsAttributesTransfers as $uuid => $apiPickingListItemsAttributesTransfer) {
            $glueResourceTransfer = (new GlueResourceTransfer())
                ->setType(PickingListsBackendApiConfig::RESOURCE_PICKING_LIST_ITEMS)
                ->setId($uuid)
                ->setAttributes($apiPickingListItemsAttributesTransfer);

            $glueRelationshipTransfer->addResource($glueResourceTransfer);
        }

        return $glueRelationshipTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListItemTransfer $pickingListItemTransfer
     * @param \Generated\Shared\Transfer\ApiPickingListItemsAttributesTransfer $apiPickingListItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiPickingListItemsAttributesTransfer
     */
    public function mapPickingListItemTransferToApiPickingListItemsAttributesTransfer(
        PickingListItemTransfer $pickingListItemTransfer,
        ApiPickingListItemsAttributesTransfer $apiPickingListItemsAttributesTransfer
    ): ApiPickingListItemsAttributesTransfer {
        $apiPickingListItemsAttributesTransfer->fromArray($pickingListItemTransfer->toArray(), true);

        if ($pickingListItemTransfer->getOrderItem()) {
            $apiOrderItemsAttributesTransfer = $this->mapItemTransferToApiOrderItemsAttributesTransfer(
                $pickingListItemTransfer->getOrderItemOrFail(),
                new ApiOrderItemsAttributesTransfer(),
            );

            $apiPickingListItemsAttributesTransfer->setOrderItem($apiOrderItemsAttributesTransfer);
        }

        return $apiPickingListItemsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ApiOrderItemsAttributesTransfer $apiOrderItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiOrderItemsAttributesTransfer
     */
    protected function mapItemTransferToApiOrderItemsAttributesTransfer(
        ItemTransfer $itemTransfer,
        ApiOrderItemsAttributesTransfer $apiOrderItemsAttributesTransfer
    ): ApiOrderItemsAttributesTransfer {
        return $apiOrderItemsAttributesTransfer->fromArray($itemTransfer->toArray(), true);
    }
}
