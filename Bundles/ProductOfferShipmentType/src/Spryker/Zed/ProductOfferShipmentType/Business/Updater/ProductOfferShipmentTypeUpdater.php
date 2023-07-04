<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Updater;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ShipmentTypeExtractorInterface;
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
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ShipmentTypeExtractorInterface
     */
    protected ShipmentTypeExtractorInterface $shipmentTypeExtractor;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeEntityManagerInterface $productOfferShipmentTypeEntityManager
     * @param \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeRepositoryInterface $productOfferShipmentTypeRepository
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ShipmentTypeExtractorInterface $shipmentTypeExtractor
     */
    public function __construct(
        ProductOfferShipmentTypeEntityManagerInterface $productOfferShipmentTypeEntityManager,
        ProductOfferShipmentTypeRepositoryInterface $productOfferShipmentTypeRepository,
        ShipmentTypeExtractorInterface $shipmentTypeExtractor
    ) {
        $this->productOfferShipmentTypeEntityManager = $productOfferShipmentTypeEntityManager;
        $this->productOfferShipmentTypeRepository = $productOfferShipmentTypeRepository;
        $this->shipmentTypeExtractor = $shipmentTypeExtractor;
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
        $persistedShipmentTypeIds = $this->productOfferShipmentTypeRepository->getShipmentTypeIdsByIdProductOffer(
            $productOfferTransfer->getIdProductOfferOrFail(),
        );
        $shipmentTypeIds = $this->shipmentTypeExtractor->extractShipmentTypeIdsFromShipmentTypeTransfers(
            $productOfferTransfer->getShipmentTypes(),
        );

        $shipmentTypeIdsToDelete = array_diff($persistedShipmentTypeIds, $shipmentTypeIds);
        $this->deleteProductOfferShipmentTypes(
            $productOfferTransfer->getIdProductOfferOrFail(),
            $shipmentTypeIdsToDelete,
        );

        $shipmentTypeIdsToCreate = array_diff($shipmentTypeIds, $persistedShipmentTypeIds);
        $this->createProductOfferShipmentTypes(
            $productOfferTransfer->getIdProductOfferOrFail(),
            $shipmentTypeIdsToCreate,
        );
    }

    /**
     * @param int $idProductOffer
     * @param list<int> $shipmentTypeIds
     *
     * @return void
     */
    protected function deleteProductOfferShipmentTypes(int $idProductOffer, array $shipmentTypeIds): void
    {
        if (!$shipmentTypeIds) {
            return;
        }

        $this->productOfferShipmentTypeEntityManager->deleteProductOfferShipmentTypes(
            $idProductOffer,
            $shipmentTypeIds,
        );
    }

    /**
     * @param int $idProductOffer
     * @param list<int> $shipmentTypeIds
     *
     * @return void
     */
    protected function createProductOfferShipmentTypes(int $idProductOffer, array $shipmentTypeIds): void
    {
        foreach ($shipmentTypeIds as $idShipmentType) {
            $this->productOfferShipmentTypeEntityManager->createProductOfferShipmentType($idProductOffer, $idShipmentType);
        }
    }
}
