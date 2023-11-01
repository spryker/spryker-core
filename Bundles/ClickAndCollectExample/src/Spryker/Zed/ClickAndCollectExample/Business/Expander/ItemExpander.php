<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Business\Expander;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointConditionsTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentMethodCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodConditionsTransfer;
use Generated\Shared\Transfer\ShipmentMethodCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToServicePointFacadeInterface;
use Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToShipmentFacadeInterface;

class ItemExpander implements ItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToServicePointFacadeInterface
     */
    protected ClickAndCollectExampleToServicePointFacadeInterface $servicePointFacade;

    /**
     * @var \Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToShipmentFacadeInterface
     */
    protected ClickAndCollectExampleToShipmentFacadeInterface $shipmentFacade;

    /**
     * @param \Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToServicePointFacadeInterface $servicePointFacade
     * @param \Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToShipmentFacadeInterface $shipmentFacade
     */
    public function __construct(
        ClickAndCollectExampleToServicePointFacadeInterface $servicePointFacade,
        ClickAndCollectExampleToShipmentFacadeInterface $shipmentFacade
    ) {
        $this->servicePointFacade = $servicePointFacade;
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemCollectionTransfer $itemCollectionTransfer
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function expandItemCollectionWithShipment(
        ItemCollectionTransfer $itemCollectionTransfer,
        CheckoutDataTransfer $checkoutDataTransfer
    ): ItemCollectionTransfer {
        $shipmentMethodIdsIndexedByItemGroupKey = $this->indexShipmentMethodIdsByItemGroupKey($checkoutDataTransfer);
        $shipmentMethodConditionsTransfer = (new ShipmentMethodConditionsTransfer())
            ->setShipmentMethodIds(array_unique(array_values($shipmentMethodIdsIndexedByItemGroupKey)));
        $shipmentMethodCollectionTransfer = $this->shipmentFacade->getShipmentMethodCollection(
            (new ShipmentMethodCriteriaTransfer())->setShipmentMethodConditions($shipmentMethodConditionsTransfer),
        );
        $shipmentMethodsIndexedById = $this->indexShipmentMethodsById($shipmentMethodCollectionTransfer);

        foreach ($itemCollectionTransfer->getItems() as $itemTransfer) {
            if (!isset($shipmentMethodIdsIndexedByItemGroupKey[$itemTransfer->getGroupKeyOrFail()])) {
                continue;
            }

            $idShipmentMethod = $shipmentMethodIdsIndexedByItemGroupKey[$itemTransfer->getGroupKeyOrFail()];

            if (!isset($shipmentMethodsIndexedById[$idShipmentMethod])) {
                continue;
            }

            $shipmentMethodTransfer = $shipmentMethodsIndexedById[$idShipmentMethod];
            $itemTransfer->setShipment((new ShipmentTransfer())->setMethod($shipmentMethodTransfer));

            if ($shipmentMethodTransfer->getShipmentType()) {
                $itemTransfer->setShipmentType($shipmentMethodTransfer->getShipmentTypeOrFail());
            }
        }

        return $itemCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemCollectionTransfer $itemCollectionTransfer
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function expandItemCollectionWithServicePoint(
        ItemCollectionTransfer $itemCollectionTransfer,
        CheckoutDataTransfer $checkoutDataTransfer
    ): ItemCollectionTransfer {
        $servicePointUuidsIndexedByItemGroupKey = $this->indexServicePointUuidsByItemGroupKey($checkoutDataTransfer);
        $servicePointConditionsTransfer = (new ServicePointConditionsTransfer())
            ->setUuids(array_unique(array_values($servicePointUuidsIndexedByItemGroupKey)));
        $servicePointCollectionTransfer = $this->servicePointFacade->getServicePointCollection(
            (new ServicePointCriteriaTransfer())
                ->setServicePointConditions($servicePointConditionsTransfer),
        );
        $servicePointsIndexedByUuid = $this->indexServicePointsByUuid($servicePointCollectionTransfer);

        foreach ($itemCollectionTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getGroupKey() || !isset($servicePointUuidsIndexedByItemGroupKey[$itemTransfer->getGroupKeyOrFail()])) {
                continue;
            }

            $servicePointUuid = $servicePointUuidsIndexedByItemGroupKey[$itemTransfer->getGroupKeyOrFail()];

            if (!isset($servicePointsIndexedByUuid[$servicePointUuid])) {
                continue;
            }

            $itemTransfer->setServicePoint($servicePointsIndexedByUuid[$servicePointUuid]);
        }

        return $itemCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer $shipmentMethodCollectionTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ShipmentMethodTransfer>
     */
    protected function indexShipmentMethodsById(ShipmentMethodCollectionTransfer $shipmentMethodCollectionTransfer): array
    {
        $shipmentMethodsIndexedById = [];

        foreach ($shipmentMethodCollectionTransfer->getShipmentMethods() as $shipmentMethodTransfer) {
            $shipmentMethodsIndexedById[$shipmentMethodTransfer->getIdShipmentMethodOrFail()] = $shipmentMethodTransfer;
        }

        return $shipmentMethodsIndexedById;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointCollectionTransfer $servicePointCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ServicePointTransfer>
     */
    protected function indexServicePointsByUuid(ServicePointCollectionTransfer $servicePointCollectionTransfer): array
    {
        $servicePointsIndexedByUuid = [];

        foreach ($servicePointCollectionTransfer->getServicePoints() as $servicePointTransfer) {
            $servicePointsIndexedByUuid[$servicePointTransfer->getUuidOrFail()] = $servicePointTransfer;
        }

        return $servicePointsIndexedByUuid;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return array<string, string>
     */
    protected function indexServicePointUuidsByItemGroupKey(CheckoutDataTransfer $checkoutDataTransfer): array
    {
        $servicePointUuidsIndexedByItemGroupKey = [];

        foreach ($checkoutDataTransfer->getServicePoints() as $restServicePointTransfer) {
            foreach ($restServicePointTransfer->getItems() as $itemGroupKey) {
                $servicePointUuidsIndexedByItemGroupKey[$itemGroupKey] = $restServicePointTransfer->getIdServicePointOrFail();
            }
        }

        return $servicePointUuidsIndexedByItemGroupKey;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return array<string, int>
     */
    protected function indexShipmentMethodIdsByItemGroupKey(CheckoutDataTransfer $checkoutDataTransfer): array
    {
        $shipmentMethodIdsIndexedByItemGroupKey = [];

        if ($checkoutDataTransfer->getShipment()) {
            foreach ($checkoutDataTransfer->getQuoteOrFail()->getItems() as $itemTransfer) {
                $shipmentMethodIdsIndexedByItemGroupKey[$itemTransfer->getGroupKeyOrFail()] = $checkoutDataTransfer->getShipmentOrFail()->getIdShipmentMethodOrFail();
            }
        }

        foreach ($checkoutDataTransfer->getShipments() as $restShipmentsTransfer) {
            foreach ($restShipmentsTransfer->getItems() as $itemGroupKey) {
                $shipmentMethodIdsIndexedByItemGroupKey[$itemGroupKey] = $restShipmentsTransfer->getIdShipmentMethodOrFail();
            }
        }

        return $shipmentMethodIdsIndexedByItemGroupKey;
    }
}
