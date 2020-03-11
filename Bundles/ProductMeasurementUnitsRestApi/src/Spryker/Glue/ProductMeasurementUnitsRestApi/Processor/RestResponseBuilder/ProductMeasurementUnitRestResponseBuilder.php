<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestProductMeasurementUnitsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\ProductMeasurementUnitsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ProductMeasurementUnitRestResponseBuilder implements ProductMeasurementUnitRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer $productMeasurementUnitTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductMeasurementUnitRestResponse(ProductMeasurementUnitTransfer $productMeasurementUnitTransfer): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse()->addResource(
            $this->createProductMeasurementUnitRestResource($productMeasurementUnitTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer $productMeasurementUnitTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createProductMeasurementUnitRestResource(ProductMeasurementUnitTransfer $productMeasurementUnitTransfer): RestResourceInterface
    {
        $restProductMeasurementUnitsAttributesTransfer = (new RestProductMeasurementUnitsAttributesTransfer())
            ->fromArray($productMeasurementUnitTransfer->toArray(), true);

        $resourceId = $productMeasurementUnitTransfer->getCode();

        return $this->restResourceBuilder->createRestResource(
            ProductMeasurementUnitsRestApiConfig::RESOURCE_PRODUCT_MEASUREMENT_UNITS,
            $resourceId,
            $restProductMeasurementUnitsAttributesTransfer
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCodeMissingErrorResponse(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductMeasurementUnitsRestApiConfig::RESPONSE_CODE_PRODUCT_MEASUREMENT_UNIT_CODE_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ProductMeasurementUnitsRestApiConfig::RESPONSE_DETAIL_MEASUREMENT_UNIT_CODE_IS_NOT_SPECIFIED);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductMeasurementUnitNotFoundErrorResponse(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductMeasurementUnitsRestApiConfig::RESPONSE_CODE_PRODUCT_MEASUREMENT_UNIT_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductMeasurementUnitsRestApiConfig::RESPONSE_DETAIL_PRODUCT_MEASUREMENT_UNIT_NOT_FOUND);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }
}
