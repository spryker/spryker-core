<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Generated\Shared\Transfer\RestProductMeasurementUnitsAttributesTransfer;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToGlossaryStorageClientInterface;

class ProductMeasurementUnitMapper implements ProductMeasurementUnitMapperInterface
{
    /**
     * @var \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(ProductMeasurementUnitsRestApiToGlossaryStorageClientInterface $glossaryStorageClient)
    {
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer $productMeasurementUniTransfer
     * @param \Generated\Shared\Transfer\RestProductMeasurementUnitsAttributesTransfer $restProductMeasurementUnitsAttributesTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestProductMeasurementUnitsAttributesTransfer
     */
    public function mapProductMeasurementUnitTransferToRestProductMeasurementUnitsAttributesTransfer(
        ProductMeasurementUnitTransfer $productMeasurementUniTransfer,
        RestProductMeasurementUnitsAttributesTransfer $restProductMeasurementUnitsAttributesTransfer,
        string $localeName
    ): RestProductMeasurementUnitsAttributesTransfer {
        return $restProductMeasurementUnitsAttributesTransfer
            ->fromArray($productMeasurementUniTransfer->toArray(), true)
            ->setMeasurementUnitCode($productMeasurementUniTransfer->getCode())
            ->setName($this->glossaryStorageClient->translate($productMeasurementUniTransfer->getName(), $localeName));
    }
}
