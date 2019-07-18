<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnitStorage\Dependency\Client;

class ProductPackagingUnitStorageToProductMeasurementUnitStorageClientBridge implements ProductPackagingUnitStorageToProductMeasurementUnitStorageClientInterface
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
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]|null
     */
    public function findProductMeasurementSalesUnitByIdProduct(int $idProduct): ?array
    {
        return $this->productMeasurementUnitStorageClient->findProductMeasurementSalesUnitByIdProduct($idProduct);
    }
}
