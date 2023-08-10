<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType\Business\Updater;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SalesShipmentType\Business\Creator\SalesShipmentTypeCreatorInterface;
use Spryker\Zed\SalesShipmentType\Business\Grouper\SalesShipmentTypeGrouperInterface;
use Spryker\Zed\SalesShipmentType\Persistence\SalesShipmentTypeEntityManagerInterface;
use Spryker\Zed\SalesShipmentType\Persistence\SalesShipmentTypeRepositoryInterface;

class SalesShipmentUpdater implements SalesShipmentUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\SalesShipmentType\Persistence\SalesShipmentTypeEntityManagerInterface
     */
    protected SalesShipmentTypeEntityManagerInterface $salesShipmentTypeEntityManager;

    /**
     * @var \Spryker\Zed\SalesShipmentType\Persistence\SalesShipmentTypeRepositoryInterface
     */
    protected SalesShipmentTypeRepositoryInterface $salesShipmentTypeRepository;

    /**
     * @var \Spryker\Zed\SalesShipmentType\Business\Creator\SalesShipmentTypeCreatorInterface
     */
    protected SalesShipmentTypeCreatorInterface $salesShipmentTypeCreator;

    /**
     * @var \Spryker\Zed\SalesShipmentType\Business\Grouper\SalesShipmentTypeGrouperInterface
     */
    protected SalesShipmentTypeGrouperInterface $salesShipmentTypeGrouper;

    /**
     * @param \Spryker\Zed\SalesShipmentType\Persistence\SalesShipmentTypeEntityManagerInterface $salesShipmentTypeEntityManager
     * @param \Spryker\Zed\SalesShipmentType\Persistence\SalesShipmentTypeRepositoryInterface $salesShipmentTypeRepository
     * @param \Spryker\Zed\SalesShipmentType\Business\Creator\SalesShipmentTypeCreatorInterface $salesShipmentTypeCreator
     * @param \Spryker\Zed\SalesShipmentType\Business\Grouper\SalesShipmentTypeGrouperInterface $salesShipmentTypeGrouper
     */
    public function __construct(
        SalesShipmentTypeEntityManagerInterface $salesShipmentTypeEntityManager,
        SalesShipmentTypeRepositoryInterface $salesShipmentTypeRepository,
        SalesShipmentTypeCreatorInterface $salesShipmentTypeCreator,
        SalesShipmentTypeGrouperInterface $salesShipmentTypeGrouper
    ) {
        $this->salesShipmentTypeEntityManager = $salesShipmentTypeEntityManager;
        $this->salesShipmentTypeRepository = $salesShipmentTypeRepository;
        $this->salesShipmentTypeCreator = $salesShipmentTypeCreator;
        $this->salesShipmentTypeGrouper = $salesShipmentTypeGrouper;
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function updateSalesShipmentsWithSalesShipmentType(
        SaveOrderTransfer $saveOrderTransfer
    ): SaveOrderTransfer {
        $shipmentTypeKeys = $this->getUniqueShipmentTypeKeysFromOrderItems($saveOrderTransfer);
        $salesShipmentTypeTransfers = $this->salesShipmentTypeRepository->getSalesShipmentTypesByKeys($shipmentTypeKeys);

        $this->getTransactionHandler()->handleTransaction(function () use ($saveOrderTransfer, $salesShipmentTypeTransfers): void {
            $this->executeUpdateSalesShipmentWithSalesShipmentTypeTransaction($saveOrderTransfer, $salesShipmentTypeTransfers);
        });

        return $saveOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param list<\Generated\Shared\Transfer\SalesShipmentTypeTransfer> $salesShipmentTypeTransfers
     *
     * @return void
     */
    protected function executeUpdateSalesShipmentWithSalesShipmentTypeTransaction(
        SaveOrderTransfer $saveOrderTransfer,
        array $salesShipmentTypeTransfers
    ): void {
        $salesShipmentTypeTransfersGroupedByKey = $this->salesShipmentTypeGrouper->getSalesShipmentTypeTransfersGroupedByKey(
            $salesShipmentTypeTransfers,
        );

        foreach ($saveOrderTransfer->getOrderItems() as $itemTransfer) {
            if (!$this->isRequiredDataProvided($itemTransfer)) {
                continue;
            }

            $shipmentTypeKey = $itemTransfer->getShipmentTypeOrFail()->getKeyOrFail();
            if (
                !isset($salesShipmentTypeTransfersGroupedByKey[$shipmentTypeKey])
                || $this->hasNotModifiedSalesShipmentType($itemTransfer->getShipmentTypeOrFail(), $salesShipmentTypeTransfersGroupedByKey[$shipmentTypeKey])
            ) {
                $salesShipmentTypeTransfersGroupedByKey[$shipmentTypeKey][] = $this->salesShipmentTypeCreator->createSalesShipmentType(
                    $itemTransfer->getShipmentTypeOrFail(),
                );
            }

            $salesShipmentTypeTransfersIndexedByName = $this->salesShipmentTypeGrouper->getSalesShipmentTypeTransfersIndexedByName(
                $salesShipmentTypeTransfersGroupedByKey[$shipmentTypeKey],
            );
            $this->salesShipmentTypeEntityManager->updateSalesShipmentWithSalesShipmentType(
                $itemTransfer->getShipmentOrFail()->getIdSalesShipmentOrFail(),
                $salesShipmentTypeTransfersIndexedByName[$itemTransfer->getShipmentTypeOrFail()->getNameOrFail()]->getIdSalesShipmentTypeOrFail(),
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return list<string>
     */
    protected function getUniqueShipmentTypeKeysFromOrderItems(SaveOrderTransfer $saveOrderTransfer): array
    {
        $shipmentTypeKeys = [];
        foreach ($saveOrderTransfer->getOrderItems() as $itemTransfer) {
            $shipmentTypeTransfer = $itemTransfer->getShipmentType();
            if ($shipmentTypeTransfer === null || isset($shipmentTypeKeys[$shipmentTypeTransfer->getKeyOrFail()])) {
                continue;
            }

            $shipmentTypeKeys[$shipmentTypeTransfer->getKeyOrFail()] = $shipmentTypeTransfer->getKeyOrFail();
        }

        return array_values($shipmentTypeKeys);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     * @param list<\Generated\Shared\Transfer\SalesShipmentTypeTransfer> $salesShipmentTypeTransfers
     *
     * @return bool
     */
    protected function hasNotModifiedSalesShipmentType(
        ShipmentTypeTransfer $shipmentTypeTransfer,
        array $salesShipmentTypeTransfers
    ): bool {
        foreach ($salesShipmentTypeTransfers as $salesShipmentTypeTransfer) {
            if ($salesShipmentTypeTransfer->getNameOrFail() !== $shipmentTypeTransfer->getNameOrFail()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isRequiredDataProvided(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getShipmentType() !== null
            && $itemTransfer->getShipment() !== null
            && $itemTransfer->getShipmentOrFail()->getIdSalesShipment() !== null;
    }
}
