<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentMethodCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;
use Spryker\Zed\ShipmentType\Business\Reader\ShipmentTypeReaderInterface;
use Spryker\Zed\ShipmentType\Persistence\ShipmentTypeRepositoryInterface;

class ShipmentMethodCollectionExpander implements ShipmentMethodCollectionExpanderInterface
{
    /**
     * @var \Spryker\Zed\ShipmentType\Business\Reader\ShipmentTypeReaderInterface
     */
    protected ShipmentTypeReaderInterface $shipmentTypeReader;

    /**
     * @var \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeRepositoryInterface
     */
    protected ShipmentTypeRepositoryInterface $shipmentTypeRepository;

    /**
     * @param \Spryker\Zed\ShipmentType\Business\Reader\ShipmentTypeReaderInterface $shipmentTypeReader
     * @param \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeRepositoryInterface $shipmentTypeRepository
     */
    public function __construct(
        ShipmentTypeReaderInterface $shipmentTypeReader,
        ShipmentTypeRepositoryInterface $shipmentTypeRepository
    ) {
        $this->shipmentTypeReader = $shipmentTypeReader;
        $this->shipmentTypeRepository = $shipmentTypeRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer $shipmentMethodCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer
     */
    public function expandWithShipmentType(ShipmentMethodCollectionTransfer $shipmentMethodCollectionTransfer): ShipmentMethodCollectionTransfer
    {
        $shipmentMethodIds = $this->extractShipmentMethodIdsFromShipmentMethodTransfers($shipmentMethodCollectionTransfer->getShipmentMethods());
        $shipmentMethodIdsGroupedByIdShipmentType = $this->shipmentTypeRepository->getShipmentMethodIdsGroupedByIdShipmentType($shipmentMethodIds);
        if ($shipmentMethodIdsGroupedByIdShipmentType === []) {
            return $shipmentMethodCollectionTransfer;
        }

        $shipmentTypeCriteriaTransfer = $this->createShipmentTypeCriteriaTransfer(array_keys($shipmentMethodIdsGroupedByIdShipmentType));
        $shipmentTypeCollectionTransfer = $this->shipmentTypeReader->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);

        $shipmentTypeTransfersIndexedByIdShipmentMethod = $this->getShipmentTypeTransfersIndexedByIdShipmentMethod(
            $shipmentTypeCollectionTransfer->getShipmentTypes(),
            $shipmentMethodIdsGroupedByIdShipmentType,
        );

        foreach ($shipmentMethodCollectionTransfer->getShipmentMethods() as $shipmentMethodTransfer) {
            $idShipmentMethod = $shipmentMethodTransfer->getIdShipmentMethodOrFail();
            if (!isset($shipmentTypeTransfersIndexedByIdShipmentMethod[$idShipmentMethod])) {
                continue;
            }

            $shipmentMethodTransfer->setShipmentType($shipmentTypeTransfersIndexedByIdShipmentMethod[$idShipmentMethod]);
        }

        return $shipmentMethodCollectionTransfer;
    }

    /**
     * @param list<int> $shipmentTypeIds
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer
     */
    protected function createShipmentTypeCriteriaTransfer(array $shipmentTypeIds): ShipmentTypeCriteriaTransfer
    {
        $shipmentTypeConditionsTransfer = (new ShipmentTypeConditionsTransfer())
            ->setWithStoreRelations(true)
            ->setShipmentTypeIds($shipmentTypeIds);

        return (new ShipmentTypeCriteriaTransfer())->setShipmentTypeConditions($shipmentTypeConditionsTransfer);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentMethodTransfer> $shipmentMethodTransfers
     *
     * @return list<int>
     */
    protected function extractShipmentMethodIdsFromShipmentMethodTransfers(ArrayObject $shipmentMethodTransfers): array
    {
        $shipmentMethodIds = [];
        foreach ($shipmentMethodTransfers as $shipmentMethodTransfer) {
            $shipmentMethodIds[] = $shipmentMethodTransfer->getIdShipmentMethodOrFail();
        }

        return $shipmentMethodIds;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     * @param array<int, list<int>> $shipmentMethodIdsGroupedByIdShipmentType
     *
     * @return array<int, \Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    protected function getShipmentTypeTransfersIndexedByIdShipmentMethod(
        ArrayObject $shipmentTypeTransfers,
        array $shipmentMethodIdsGroupedByIdShipmentType
    ): array {
        $indexedShipmentTypeTransfers = [];
        foreach ($shipmentTypeTransfers as $shipmentTypeTransfer) {
            $shipmentMethodIds = $shipmentMethodIdsGroupedByIdShipmentType[$shipmentTypeTransfer->getIdShipmentTypeOrFail()];
            $indexedShipmentTypeTransfers += array_fill_keys($shipmentMethodIds, $shipmentTypeTransfer);
        }

        return $indexedShipmentTypeTransfers;
    }
}
