<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Updater;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeEntityManagerInterface;
use Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeRepositoryInterface;

class ProductOfferShipmentTypeUpdater implements ProductOfferShipmentTypeUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeEntityManagerInterface
     */
    protected ProductOfferShipmentTypeEntityManagerInterface $productOfferShipmentTypeEntityManager;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeRepositoryInterface
     */
    protected ProductOfferShipmentTypeRepositoryInterface $productOfferShipmentTypeRepository;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeEntityManagerInterface $productOfferShipmentTypeEntityManager
     * @param \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeRepositoryInterface $productOfferShipmentTypeRepository
     */
    public function __construct(
        ProductOfferShipmentTypeEntityManagerInterface $productOfferShipmentTypeEntityManager,
        ProductOfferShipmentTypeRepositoryInterface $productOfferShipmentTypeRepository
    ) {
        $this->productOfferShipmentTypeEntityManager = $productOfferShipmentTypeEntityManager;
        $this->productOfferShipmentTypeRepository = $productOfferShipmentTypeRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function updateProductOfferShipmentTypes(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($productOfferTransfer): void {
            $this->executeUpdateProductOfferShipmentTypesTransaction($productOfferTransfer);
        });

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return void
     */
    protected function executeUpdateProductOfferShipmentTypesTransaction(ProductOfferTransfer $productOfferTransfer): void
    {
        $persistedShipmentTypeUuids = $this->productOfferShipmentTypeRepository->getShipmentTypeUuidsByProductOfferReference(
            $productOfferTransfer->getProductOfferReferenceOrFail(),
        );
        $shipmentTypeUuids = $this->extractShipmentTypeUuids($productOfferTransfer->getShipmentTypes());

        $shipmentTypeUuidsToDelete = array_diff($persistedShipmentTypeUuids, $shipmentTypeUuids);
        $this->deleteProductOfferShipmentTypes(
            $productOfferTransfer->getProductOfferReferenceOrFail(),
            $shipmentTypeUuidsToDelete,
        );

        $shipmentTypeUuidsToCreate = array_diff($shipmentTypeUuids, $persistedShipmentTypeUuids);
        $this->createProductOfferShipmentTypes(
            $productOfferTransfer->getProductOfferReferenceOrFail(),
            $shipmentTypeUuidsToCreate,
        );
    }

    /**
     * @param string $productOfferReference
     * @param array<string> $shipmentTypeUuids
     *
     * @return void
     */
    protected function deleteProductOfferShipmentTypes(string $productOfferReference, array $shipmentTypeUuids): void
    {
        if (!$shipmentTypeUuids) {
            return;
        }

        $this->productOfferShipmentTypeEntityManager->deleteProductOfferShipmentTypes(
            $productOfferReference,
            $shipmentTypeUuids,
        );
    }

    /**
     * @param string $productOfferReference
     * @param array<string> $shipmentTypeUuids
     *
     * @return void
     */
    protected function createProductOfferShipmentTypes(string $productOfferReference, array $shipmentTypeUuids): void
    {
        foreach ($shipmentTypeUuids as $shipmentTypeUuid) {
            $this->productOfferShipmentTypeEntityManager->createProductOfferShipmentType($productOfferReference, $shipmentTypeUuid);
        }
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     *
     * @return array<string>
     */
    protected function extractShipmentTypeUuids(ArrayObject $shipmentTypeTransfers): array
    {
        $shipmentTypeUuids = [];
        foreach ($shipmentTypeTransfers as $shipmentTypeTransfer) {
            $shipmentTypeUuids[] = $shipmentTypeTransfer->getUuidOrFail();
        }

        return $shipmentTypeUuids;
    }
}
