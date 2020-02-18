<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Reader;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\ProductMeasurementUnitRestResponseBuilderInterface;

class ProductMeasurementUnitReader implements ProductMeasurementUnitReaderInterface
{
    protected const PRODUCT_MEASUREMENT_UNIT_MAPPING_TYPE = 'code';

    /**
     * @var \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\ProductMeasurementUnitRestResponseBuilderInterface
     */
    protected $productMeasurementUnitRestResponseBuilder;

    /**
     * @var \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface
     */
    protected $productMeasurementUnitStorageClient;

    /**
     * @param \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\ProductMeasurementUnitRestResponseBuilderInterface $productMeasurementUnitRestResponseBuilder
     * @param \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface $productMeasurementUnitStorageClient
     */
    public function __construct(
        ProductMeasurementUnitRestResponseBuilderInterface $productMeasurementUnitRestResponseBuilder,
        ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface $productMeasurementUnitStorageClient
    ) {
        $this->productMeasurementUnitRestResponseBuilder = $productMeasurementUnitRestResponseBuilder;
        $this->productMeasurementUnitStorageClient = $productMeasurementUnitStorageClient;
    }
    
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getProductMeasurementUnit(RestRequestInterface $restRequest): RestResponseInterface
    {
        if (!$restRequest->getResource()->getId()) {
            return $this->productMeasurementUnitRestResponseBuilder->createCodeMissingErrorResponse();
        }

        $productMeasurementUnitTransfers = $this->productMeasurementUnitStorageClient->getProductMeasurementUnitsByMapping(
            static::PRODUCT_MEASUREMENT_UNIT_MAPPING_TYPE,
            [$restRequest->getResource()->getId()]
        );

        if (!$productMeasurementUnitTransfers) {
            return $this->productMeasurementUnitRestResponseBuilder->createProductMeasurementUnitNotFoundErrorResponse();
        }

        return $this->productMeasurementUnitRestResponseBuilder->createProductMeasurementUnitRestResponse(
            $productMeasurementUnitTransfers[0],
            $restRequest->getMetadata()->getLocale()
        );
    }
}
