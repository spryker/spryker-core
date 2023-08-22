<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\PickingListsShipmentsBackendResourceRelationship;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\PickingListBuilder;
use Generated\Shared\DataBuilder\PickingListItemBuilder;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Propel\Runtime\Collection\ObjectCollection;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(\SprykerTest\Glue\PickingListsShipmentsBackendResourceRelationship\PHPMD)
 */
class PickingListsShipmentsBackendResourceRelationshipTester extends Actor
{
    use _generated\PickingListsShipmentsBackendResourceRelationshipTesterActions;

    /**
     * @uses \Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig::RESOURCE_PICKING_LIST_ITEMS
     *
     * @var string
     */
    protected const RESOURCE_PICKING_LIST_ITEMS = 'picking-list-items';

    /**
     * @return list<\Generated\Shared\Transfer\PickingListTransfer|\Generated\Shared\Transfer\ShipmentTransfer>
     */
    public function createPickingListWithItemAndShipment(): array
    {
        $itemTransfer = (new ItemBuilder([]))->build();
        $salesOrderEntity = $this->haveSalesOrderEntity([$itemTransfer]);
        $shipmentTransfer = $this->haveShipment($salesOrderEntity->getIdSalesOrder());

        $this->updateSalesOrderItemsWithIdShipment($shipmentTransfer, $salesOrderEntity->getItems());
        $itemTransfer
            ->fromArray($salesOrderEntity->getItems()->getFirst()->toArray(), true)
            ->setShipment($shipmentTransfer);

        $pickingListTransfer = (new PickingListBuilder([
            PickingListTransfer::WAREHOUSE => $this->haveStock()->toArray(),
        ]))->withPickingListItem((new PickingListItemBuilder([
            PickingListItemTransfer::ORDER_ITEM => $itemTransfer->toArray(),
            PickingListItemTransfer::QUANTITY => 1,
            PickingListItemTransfer::ID_PICKING_LIST => null,
        ])))->build();

        $pickingListTransfer = $this->havePickingList($pickingListTransfer);

        return [$pickingListTransfer, $shipmentTransfer];
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemTransfers
     * @param list<\Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer> $pickingListItemsAttributesTransfers
     *
     * @return list<\Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer>
     */
    public function mapPickingListItemsToPickingListItemsBackendApiAttributesTransfers(
        ArrayObject $pickingListItemTransfers,
        array $pickingListItemsAttributesTransfers = []
    ): array {
        foreach ($pickingListItemTransfers as $pickingListItemTransfer) {
            $pickingListItemsAttributesTransfers[] = (new PickingListItemsBackendApiAttributesTransfer())
                ->fromArray($pickingListItemTransfer->toArray(), true);
        }

        return $pickingListItemsAttributesTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer> $pickingListItemsAttributesTransfers
     *
     * @return array<\Generated\Shared\Transfer\GlueResourceTransfer>
     */
    public function addPickingListItemsRelationshipResourceToGlueResourceTransfers(array $pickingListItemsAttributesTransfers): array
    {
        $glueResourceTransfers = [];

        foreach ($pickingListItemsAttributesTransfers as $pickingListItemsAttributesTransfer) {
            $glueResourceTransfers[] = (new GlueResourceTransfer())
                ->setType(static::RESOURCE_PICKING_LIST_ITEMS)
                ->setAttributes($pickingListItemsAttributesTransfer);
        }

        return $glueResourceTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Propel\Runtime\Collection\ObjectCollection<array-key, \Orm\Zed\Sales\Persistence\SpySalesOrderItem> $salesOrderItemEntities
     *
     * @return void
     */
    protected function updateSalesOrderItemsWithIdShipment(ShipmentTransfer $shipmentTransfer, ObjectCollection $salesOrderItemEntities): void
    {
        foreach ($salesOrderItemEntities as $salesOrderItemEntity) {
            $salesOrderItemEntity->setFkSalesShipment($shipmentTransfer->getIdSalesShipment());
            $salesOrderItemEntity->save();
        }
    }
}
