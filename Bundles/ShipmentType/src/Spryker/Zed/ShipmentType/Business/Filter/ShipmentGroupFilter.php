<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Zed\ShipmentType\Persistence\ShipmentTypeRepositoryInterface;

class ShipmentGroupFilter implements ShipmentGroupFilterInterface
{
    /**
     * @var \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeRepositoryInterface
     */
    protected ShipmentTypeRepositoryInterface $shipmentTypeRepository;

    /**
     * @param \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeRepositoryInterface $shipmentTypeRepository
     */
    public function __construct(ShipmentTypeRepositoryInterface $shipmentTypeRepository)
    {
        $this->shipmentTypeRepository = $shipmentTypeRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ShipmentMethodTransfer>
     */
    public function filterShipmentGroupMethods(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        QuoteTransfer $quoteTransfer
    ): ArrayObject {
        /** @var \ArrayObject<int, \Generated\Shared\Transfer\ShipmentMethodTransfer> $shipmentMethodTransfers */
        $shipmentMethodTransfers = $shipmentGroupTransfer->getAvailableShipmentMethodsOrFail()->getMethods();

        $shipmentTypeUuids = $this->extractShipmentTypeUuidsFromItemTransfers($shipmentGroupTransfer->getItems());
        if ($shipmentTypeUuids === []) {
            return $shipmentMethodTransfers;
        }
        $availableShipmentMethodIds = $this->shipmentTypeRepository->getShipmentMethodIdsByShipmentTypeConditions(
            $shipmentTypeUuids,
            $quoteTransfer->getStoreOrFail()->getNameOrFail(),
        );
        if ($availableShipmentMethodIds === []) {
            return new ArrayObject();
        }

        return $this->filterOutUnavailableShipmentMethodTransfers($shipmentMethodTransfers, $availableShipmentMethodIds);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<string>
     */
    protected function extractShipmentTypeUuidsFromItemTransfers(ArrayObject $itemTransfers): array
    {
        $shipmentTypeUuids = [];
        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getShipmentType() === null) {
                continue;
            }
            $shipmentTypeUuids[] = $itemTransfer->getShipmentTypeOrFail()->getUuidOrFail();
        }

        return array_unique($shipmentTypeUuids);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ShipmentMethodTransfer> $shipmentMethodTransfers
     * @param list<int> $availableShipmentMethodIds
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ShipmentMethodTransfer>
     */
    protected function filterOutUnavailableShipmentMethodTransfers(
        ArrayObject $shipmentMethodTransfers,
        array $availableShipmentMethodIds
    ): ArrayObject {
        $availableShipmentMethodTransfers = new ArrayObject();
        foreach ($shipmentMethodTransfers as $index => $shipmentMethodTransfer) {
            if (in_array($shipmentMethodTransfer->getIdShipmentMethodOrFail(), $availableShipmentMethodIds, true)) {
                $availableShipmentMethodTransfers->offsetSet($index, $shipmentMethodTransfer);
            }
        }

        return $availableShipmentMethodTransfers;
    }
}
