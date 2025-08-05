<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ProductShipmentTypeReaderInterface;

class ProductConcreteShipmentTypeExpander implements ProductConcreteShipmentTypeExpanderInterface
{
    public function __construct(
        protected ProductShipmentTypeReaderInterface $productShipmentTypeReader
    ) {
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return list<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expandProductConcreteTransfersWithShipmentTypes(array $productConcreteTransfers): array
    {
        if (!$productConcreteTransfers) {
            return $productConcreteTransfers;
        }

        $productConcreteIds = $this->extractProductConcreteIds($productConcreteTransfers);
        $shipmentTypesGroupedByIdProductConcrete = $this->productShipmentTypeReader->getShipmentTypesGroupedByIdProductConcrete($productConcreteIds);

        return $this->expandProductsWithShipmentTypes($productConcreteTransfers, $shipmentTypesGroupedByIdProductConcrete);
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return list<int>
     */
    protected function extractProductConcreteIds(array $productConcreteTransfers): array
    {
        return array_map(
            static fn (ProductConcreteTransfer $productConcreteTransfer): int => $productConcreteTransfer->getIdProductConcreteOrFail(),
            $productConcreteTransfers,
        );
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     * @param array<int, list<\Generated\Shared\Transfer\ShipmentTypeTransfer>> $shipmentTypesGroupedByIdProductConcrete
     *
     * @return list<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function expandProductsWithShipmentTypes(
        array $productConcreteTransfers,
        array $shipmentTypesGroupedByIdProductConcrete
    ): array {
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $idProductConcrete = $productConcreteTransfer->getIdProductConcreteOrFail();
            if (!isset($shipmentTypesGroupedByIdProductConcrete[$idProductConcrete])) {
                continue;
            }

            $productConcreteTransfer->setShipmentTypes(
                new ArrayObject($shipmentTypesGroupedByIdProductConcrete[$idProductConcrete]),
            );
        }

        return $productConcreteTransfers;
    }
}
