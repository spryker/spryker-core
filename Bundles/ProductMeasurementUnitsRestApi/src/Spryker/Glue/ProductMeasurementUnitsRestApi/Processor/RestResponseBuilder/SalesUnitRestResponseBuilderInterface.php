<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface SalesUnitRestResponseBuilderInterface
{
    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createRestResponse(): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
     * @param string $concreteProductResourceId
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createSalesUnitRestResource(
        ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer,
        string $concreteProductResourceId
    ): RestResourceInterface;

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[] $productMeasurementSalesUnitTransfers
     * @param string $concreteProductResourceId
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createSalesUnitResourceCollectionResponse(
        array $productMeasurementSalesUnitTransfers,
        string $concreteProductResourceId
    ): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductConcreteSkuMissingErrorResponse(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductConcreteNotFoundErrorResponse(): RestResponseInterface;
}
