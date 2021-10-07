<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client;

interface ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface
{
    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementUnitTransfer>
     */
    public function getProductMeasurementBaseUnitsByProductConcreteIds(array $productConcreteIds): array;

    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteProductMeasurementSalesUnitTransfer>
     */
    public function getProductMeasurementSalesUnitsByProductConcreteIds(array $productConcreteIds): array;

    /**
     * @param string $mappingType
     * @param array<string> $identifiers
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementUnitTransfer>
     */
    public function getProductMeasurementUnitsByMapping(string $mappingType, array $identifiers): array;
}
