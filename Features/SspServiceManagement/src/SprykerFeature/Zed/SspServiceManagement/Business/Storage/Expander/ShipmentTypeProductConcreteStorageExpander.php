<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Business\Storage\Expander;

use Generated\Shared\Transfer\ProductConcreteStorageTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use SprykerFeature\Zed\SspServiceManagement\Business\Reader\ProductShipmentTypeReaderInterface;
use SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementRepositoryInterface;

class ShipmentTypeProductConcreteStorageExpander implements ShipmentTypeProductConcreteStorageExpanderInterface
{
    /**
     * @param \SprykerFeature\Zed\SspServiceManagement\Business\Reader\ProductShipmentTypeReaderInterface $productShipmentTypeReader
     * @param \SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementRepositoryInterface $serviceManagementRepository
     */
    public function __construct(
        protected ProductShipmentTypeReaderInterface $productShipmentTypeReader,
        protected SspServiceManagementRepositoryInterface $serviceManagementRepository
    ) {
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductConcreteStorageTransfer> $productConcreteStorageTransfers
     *
     * @return list<\Generated\Shared\Transfer\ProductConcreteStorageTransfer>
     */
    public function expandProductConcreteStorageTransfersWithShipmentTypes(
        array $productConcreteStorageTransfers
    ): array {
        if (!$productConcreteStorageTransfers) {
            return $productConcreteStorageTransfers;
        }

        $productConcreteIds = $this->extractProductConcreteIds($productConcreteStorageTransfers);
        $productAbstractIds = $this->extractProductAbstractIds($productConcreteStorageTransfers);

        $productAbstractTypeTransfers = $this->serviceManagementRepository->getProductAbstractTypesByProductAbstractIds($productAbstractIds);

        $this->expandProductsWithProductTypes($productConcreteStorageTransfers, $productAbstractTypeTransfers);

        $shipmentTypesGroupedByIdProductConcrete = $this->productShipmentTypeReader->getShipmentTypesGroupedByIdProductConcrete($productConcreteIds);

        return $this->expandProductsWithShipmentTypeUuids($productConcreteStorageTransfers, $shipmentTypesGroupedByIdProductConcrete);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer> $productConcreteStorageTransfers
     * @param array<\Generated\Shared\Transfer\ProductAbstractTypeTransfer> $productAbstractTypeTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer>
     */
    protected function expandProductsWithProductTypes(array $productConcreteStorageTransfers, array $productAbstractTypeTransfers): array
    {
        $productAbstractTypeNamesIndexedByIdProductAbstract = [];

        foreach ($productAbstractTypeTransfers as $productAbstractTypeTransfer) {
            foreach ($productAbstractTypeTransfer->getFkProductAbstracts() as $fkProductAbstract) {
                $productAbstractTypeNamesIndexedByIdProductAbstract[$fkProductAbstract][] = $productAbstractTypeTransfer->getName();
            }
        }

        foreach ($productConcreteStorageTransfers as $productConcreteStorageTransfer) {
            /**
             * @var array<string>|null $productTypes
             */
            $productTypes = $productAbstractTypeNamesIndexedByIdProductAbstract[$productConcreteStorageTransfer->getIdProductAbstractOrFail()] ?? [];
            $productConcreteStorageTransfer->setProductTypes($productTypes);
        }

        return $productConcreteStorageTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductConcreteStorageTransfer> $productConcreteStorageTransfers
     *
     * @return list<int>
     */
    protected function extractProductConcreteIds(array $productConcreteStorageTransfers): array
    {
        return array_map(
            static fn (ProductConcreteStorageTransfer $productConcreteStorageTransfer): int => $productConcreteStorageTransfer->getIdProductConcreteOrFail(),
            $productConcreteStorageTransfers,
        );
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductConcreteStorageTransfer> $productConcreteStorageTransfers
     *
     * @return list<int>
     */
    protected function extractProductAbstractIds(array $productConcreteStorageTransfers): array
    {
        return array_map(
            static fn (ProductConcreteStorageTransfer $productConcreteStorageTransfer): int => $productConcreteStorageTransfer->getIdProductAbstractOrFail(),
            $productConcreteStorageTransfers,
        );
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductConcreteStorageTransfer> $productConcreteStorageTransfers
     * @param array<int, list<\Generated\Shared\Transfer\ShipmentTypeTransfer>> $shipmentTypesGroupedByIdProductConcrete
     *
     * @return list<\Generated\Shared\Transfer\ProductConcreteStorageTransfer>
     */
    protected function expandProductsWithShipmentTypeUuids(
        array $productConcreteStorageTransfers,
        array $shipmentTypesGroupedByIdProductConcrete
    ): array {
        foreach ($productConcreteStorageTransfers as $productConcreteStorageTransfer) {
            $idProductConcrete = $productConcreteStorageTransfer->getIdProductConcreteOrFail();
            if (!isset($shipmentTypesGroupedByIdProductConcrete[$idProductConcrete])) {
                continue;
            }

            $shipmentTypeUuids = $this->extractShipmentTypeUuids($shipmentTypesGroupedByIdProductConcrete[$idProductConcrete]);
            $productConcreteStorageTransfer->setShipmentTypeUuids($shipmentTypeUuids);
        }

        return $productConcreteStorageTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     *
     * @return list<string>
     */
    protected function extractShipmentTypeUuids(array $shipmentTypeTransfers): array
    {
        return array_map(
            static fn (ShipmentTypeTransfer $shipmentTypeTransfer): string => $shipmentTypeTransfer->getUuidOrFail(),
            $shipmentTypeTransfers,
        );
    }
}
