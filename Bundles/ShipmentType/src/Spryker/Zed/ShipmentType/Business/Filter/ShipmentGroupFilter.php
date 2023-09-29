<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
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

        $shipmentTypeUuids = $this->extractShipmentTypeUuids($shipmentGroupTransfer->getItems());
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
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<string>
     */
    protected function extractShipmentTypeUuids(ArrayObject $itemTransfers): array
    {
        return array_unique(
            array_merge(
                $this->extractShipmentTypeUuidsFromItemTransfers($itemTransfers),
                $this->extractShipmentTypeUuidsFromItemsShipment($itemTransfers),
            ),
        );
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

        return $shipmentTypeUuids;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<string>
     */
    protected function extractShipmentTypeUuidsFromItemsShipment(ArrayObject $itemTransfers): array
    {
        $shipmentTypeUuids = [];
        foreach ($itemTransfers as $itemTransfer) {
            if (!$this->hasShipmentTypeInShipment($itemTransfer)) {
                continue;
            }
            $shipmentTransfer = $itemTransfer->getShipmentOrFail();
            $shipmentTypeUuids[] = $shipmentTransfer->getMethodOrFail()->getShipmentTypeOrFail()->getUuidOrFail();
        }

        return $shipmentTypeUuids;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function hasShipmentTypeInShipment(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getShipment()
            && $itemTransfer->getShipmentOrFail()->getMethod()
            && $itemTransfer->getShipmentOrFail()->getMethodOrFail()->getShipmentType();
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
