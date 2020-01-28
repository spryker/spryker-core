<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\RestSalesUnitsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Mapper\SalesUnitMapperInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\ProductMeasurementUnitsRestApiConfig;

class SalesUnitRestResponseBuilder implements SalesUnitRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Mapper\SalesUnitMapperInterface
     */
    protected $salesUnitMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Mapper\SalesUnitMapperInterface $salesUnitMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        SalesUnitMapperInterface $salesUnitMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->salesUnitMapper = $salesUnitMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createSalesUnitRestResponse(ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        return $restResponse->addResource($this->createSalesUnitRestResource($productMeasurementSalesUnitTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createSalesUnitRestResource(ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer): RestResourceInterface
    {
        $restProductMeasurementUnitsAttributesTransfer = $this->salesUnitMapper
            ->mapProductMeasurementSalesUnitTransferToRestSalesUnitsAttributesTransfer(
                $productMeasurementSalesUnitTransfer,
                new RestSalesUnitsAttributesTransfer()
            );

        $resourceId = (string)$productMeasurementSalesUnitTransfer->getIdProductMeasurementSalesUnit();

        return $this->restResourceBuilder->createRestResource(
            ProductMeasurementUnitsRestApiConfig::RESOURCE_SALES_UNITS,
            $resourceId,
            $restProductMeasurementUnitsAttributesTransfer
        );
    }
}
