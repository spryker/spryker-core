<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderItemsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;
use Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig;

class PickingListItemMapper implements PickingListItemMapperInterface
{
    /**
     * @var list<\Spryker\Glue\PickingListsBackendApiExtension\Dependency\Plugin\PickingListItemsBackendApiAttributesMapperPluginInterface>
     */
    protected array $pickingListItemsBackendApiAttributesMapperPlugins;

    /**
     * @param list<\Spryker\Glue\PickingListsBackendApiExtension\Dependency\Plugin\PickingListItemsBackendApiAttributesMapperPluginInterface> $pickingListItemsBackendApiAttributesMapperPlugins
     */
    public function __construct(array $pickingListItemsBackendApiAttributesMapperPlugins)
    {
        $this->pickingListItemsBackendApiAttributesMapperPlugins = $pickingListItemsBackendApiAttributesMapperPlugins;
    }

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

        /** @var \Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer|null $pickingListItemsBackendApiAttributesTransfer */
        $pickingListItemsBackendApiAttributesTransfer = $glueResourceTransfer->getAttributes();
        if (!$pickingListItemsBackendApiAttributesTransfer) {
            return $pickingListItemTransfer;
        }

        $pickingListItemTransfer->setNumberOfPicked(
            $pickingListItemsBackendApiAttributesTransfer->getNumberOfPicked(),
        )->setNumberOfNotPicked(
            $pickingListItemsBackendApiAttributesTransfer->getNumberOfNotPicked(),
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
        $pickingListItemsBackendApiAttributesTransfers = [];
        foreach ($pickingListItemTransfers as $pickingListItemTransfer) {
            $pickingListItemsBackendApiAttributesTransfers[$pickingListItemTransfer->getUuidOrFail()] = $this->mapPickingListItemTransferToPickingListItemsBackendApiAttributesTransfer(
                $pickingListItemTransfer,
                new PickingListItemsBackendApiAttributesTransfer(),
            );
        }

        $pickingListItemsBackendApiAttributesTransfers = $this->executePickingListItemsBackendApiAttributesMapperPlugins(
            $pickingListItemTransfers,
            $pickingListItemsBackendApiAttributesTransfers,
        );

        foreach ($pickingListItemsBackendApiAttributesTransfers as $uuid => $pickingListItemsBackendApiAttributesTransfer) {
            $glueResourceTransfer = (new GlueResourceTransfer())
                ->setType(PickingListsBackendApiConfig::RESOURCE_PICKING_LIST_ITEMS)
                ->setId($uuid)
                ->setAttributes($pickingListItemsBackendApiAttributesTransfer);

            $glueRelationshipTransfer->addResource($glueResourceTransfer);
        }

        return $glueRelationshipTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListItemTransfer $pickingListItemTransfer
     * @param \Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer $pickingListItemsBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer
     */
    public function mapPickingListItemTransferToPickingListItemsBackendApiAttributesTransfer(
        PickingListItemTransfer $pickingListItemTransfer,
        PickingListItemsBackendApiAttributesTransfer $pickingListItemsBackendApiAttributesTransfer
    ): PickingListItemsBackendApiAttributesTransfer {
        $pickingListItemsBackendApiAttributesTransfer->fromArray($pickingListItemTransfer->toArray(), true);

        if ($pickingListItemTransfer->getOrderItem()) {
            $orderItemsBackendApiAttributesTransfer = $this->mapItemTransferToOrderItemsBackendApiAttributesTransfer(
                $pickingListItemTransfer->getOrderItemOrFail(),
                new OrderItemsBackendApiAttributesTransfer(),
            );

            $pickingListItemsBackendApiAttributesTransfer->setOrderItem($orderItemsBackendApiAttributesTransfer);
        }

        return $pickingListItemsBackendApiAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\OrderItemsBackendApiAttributesTransfer $orderItemsBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\OrderItemsBackendApiAttributesTransfer
     */
    protected function mapItemTransferToOrderItemsBackendApiAttributesTransfer(
        ItemTransfer $itemTransfer,
        OrderItemsBackendApiAttributesTransfer $orderItemsBackendApiAttributesTransfer
    ): OrderItemsBackendApiAttributesTransfer {
        return $orderItemsBackendApiAttributesTransfer->fromArray($itemTransfer->toArray(), true);
    }

    /**
     * @param list<\Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemTransfers
     * @param array<string, \Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer> $pickingListItemsBackendApiAttributesTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer>
     */
    protected function executePickingListItemsBackendApiAttributesMapperPlugins(
        array $pickingListItemTransfers,
        array $pickingListItemsBackendApiAttributesTransfers
    ): array {
        foreach ($this->pickingListItemsBackendApiAttributesMapperPlugins as $pickingListItemsBackendApiAttributesMapperPlugin) {
            $pickingListItemsBackendApiAttributesTransfers = $pickingListItemsBackendApiAttributesMapperPlugin->mapPickingListItemTransfersToPickingListItemsBackendApiAttributesTransfers(
                $pickingListItemTransfers,
                $pickingListItemsBackendApiAttributesTransfers,
            );
        }

        return $pickingListItemsBackendApiAttributesTransfers;
    }
}
