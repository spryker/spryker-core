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
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Translator\ProductMeasurementUnitNameTranslatorInterface;

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
     * @var \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Translator\ProductMeasurementUnitNameTranslatorInterface
     */
    protected $productMeasurementUnitNameTranslator;

    /**
     * @param \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\ProductMeasurementUnitRestResponseBuilderInterface $productMeasurementUnitRestResponseBuilder
     * @param \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface $productMeasurementUnitStorageClient
     * @param \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Translator\ProductMeasurementUnitNameTranslatorInterface $productMeasurementUnitNameTranslator
     */
    public function __construct(
        ProductMeasurementUnitRestResponseBuilderInterface $productMeasurementUnitRestResponseBuilder,
        ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface $productMeasurementUnitStorageClient,
        ProductMeasurementUnitNameTranslatorInterface $productMeasurementUnitNameTranslator
    ) {
        $this->productMeasurementUnitRestResponseBuilder = $productMeasurementUnitRestResponseBuilder;
        $this->productMeasurementUnitStorageClient = $productMeasurementUnitStorageClient;
        $this->productMeasurementUnitNameTranslator = $productMeasurementUnitNameTranslator;
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

        $productMeasurementUnitTransfer = $this->productMeasurementUnitNameTranslator
            ->getProductMeasurementUnitTransfersWithTranslatedNames(
                $productMeasurementUnitTransfers,
                $restRequest->getMetadata()->getLocale()
            )[0];

        return $this->productMeasurementUnitRestResponseBuilder->createProductMeasurementUnitRestResponse($productMeasurementUnitTransfer);
    }
}
