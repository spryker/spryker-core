<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client;

class ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientBridge implements ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageClientInterface
     */
    protected $productMeasurementUnitStorageClient;

    /**
     * @param \Spryker\Client\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageClientInterface $productMeasurementUnitStorageClient
     */
    public function __construct($productMeasurementUnitStorageClient)
    {
        $this->productMeasurementUnitStorageClient = $productMeasurementUnitStorageClient;
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    public function getProductMeasurementBaseUnitsByProductConcreteIds(array $productConcreteIds): array
    {
        return $this->productMeasurementUnitStorageClient
            ->getProductMeasurementBaseUnitsByProductConcreteIds($productConcreteIds);
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\ProductConcreteProductMeasurementSalesUnitTransfer[]
     */
    public function getProductMeasurementSalesUnitsByProductConcreteIds(array $productConcreteIds): array
    {
        return $this->productMeasurementUnitStorageClient
            ->getProductMeasurementSalesUnitsByProductConcreteIds($productConcreteIds);
    }

    /**
     * @param string $mappingType
     * @param string[] $identifiers
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    public function getProductMeasurementUnitsByMapping(string $mappingType, array $identifiers): array
    {
        return $this->productMeasurementUnitStorageClient->getProductMeasurementUnitsByMapping($mappingType, $identifiers);
    }
}
