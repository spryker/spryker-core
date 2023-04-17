<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Business\Generator;

use ArrayObject;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;
use Generated\Shared\Transfer\PickingListOrderItemGroupTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Dependency\Facade\PickingListMultiShipmentPickingStrategyExampleToShipmentFacadeInterface;
use Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Dependency\Service\PickingListMultiShipmentPickingStrategyExampleToShipmentServiceInterface;

class MultiShipmentPickingListGenerator implements MultiShipmentPickingListGeneratorInterface
{
    /**
     * @var \Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Dependency\Facade\PickingListMultiShipmentPickingStrategyExampleToShipmentFacadeInterface
     */
    protected PickingListMultiShipmentPickingStrategyExampleToShipmentFacadeInterface $shipmentFacade;

    /**
     * @var \Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Dependency\Service\PickingListMultiShipmentPickingStrategyExampleToShipmentServiceInterface
     */
    protected PickingListMultiShipmentPickingStrategyExampleToShipmentServiceInterface $shipmentService;

    /**
     * @param \Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Dependency\Facade\PickingListMultiShipmentPickingStrategyExampleToShipmentFacadeInterface $shipmentFacade
     * @param \Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Dependency\Service\PickingListMultiShipmentPickingStrategyExampleToShipmentServiceInterface $shipmentService
     */
    public function __construct(
        PickingListMultiShipmentPickingStrategyExampleToShipmentFacadeInterface $shipmentFacade,
        PickingListMultiShipmentPickingStrategyExampleToShipmentServiceInterface $shipmentService
    ) {
        $this->shipmentFacade = $shipmentFacade;
        $this->shipmentService = $shipmentService;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListOrderItemGroupTransfer $pickingListOrderItemGroupTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function generatePickingLists(PickingListOrderItemGroupTransfer $pickingListOrderItemGroupTransfer): PickingListCollectionTransfer
    {
        $itemTransfers = $this->shipmentFacade->expandOrderItemsWithShipment(
            $pickingListOrderItemGroupTransfer->getOrderItems()->getArrayCopy(),
        );

        $shipmentGroupTransfers = $this->shipmentService->groupItemsByShipment($itemTransfers);

        $pickingListCollectionTransfer = new PickingListCollectionTransfer();
        foreach ($shipmentGroupTransfers as $shipmentGroupTransfer) {
            $pickingLists = $this->generatePickingListForShipmentGroup(
                $shipmentGroupTransfer,
                $pickingListOrderItemGroupTransfer->getWarehouseOrFail(),
            );

            $pickingListCollectionTransfer->addPickingList($pickingLists);
        }

        return $pickingListCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    protected function generatePickingListForShipmentGroup(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        StockTransfer $stockTransfer
    ): PickingListTransfer {
        $pickingListItemTransfers = [];

        foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
            $pickingListItemTransfers[] = (new PickingListItemTransfer())
                ->setQuantity($itemTransfer->getQuantity())
                ->setOrderItem($itemTransfer)
                ->setNumberOfNotPicked(0)
                ->setNumberOfPicked(0);
        }

        return (new PickingListTransfer())
            ->setWarehouse($stockTransfer)
            ->setPickingListItems(new ArrayObject($pickingListItemTransfers));
    }
}
