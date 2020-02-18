<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\ProductMeasurementUnitRestResponseBuilderInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Translator\ProductMeasurementUnitNameTranslatorInterface;

class ProductMeasurementUnitBySalesUnitResourceRelationshipExpander implements ProductMeasurementUnitBySalesUnitResourceRelationshipExpanderInterface
{
    protected const PRODUCT_CONCRETE_MAPPING_TYPE = 'sku';
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
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $codes = $this->getAllCodes($resources);
        $productMeasurementUnitTransfers = $this->productMeasurementUnitStorageClient
            ->getProductMeasurementUnitsByMapping(
                static::PRODUCT_MEASUREMENT_UNIT_MAPPING_TYPE,
                $codes
            );

        foreach ($resources as $resource) {
            $this->addProductMeasurementUnitResourceRelationships(
                $productMeasurementUnitTransfers,
                $resource,
                $restRequest->getMetadata()->getLocale()
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[] $productMeasurementUnitTransfers
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param string $localeName
     *
     * @return void
     */
    protected function addProductMeasurementUnitResourceRelationships(
        array $productMeasurementUnitTransfers,
        RestResourceInterface $resource,
        string $localeName
    ): void {
        $productMeasurementUnitTransfersWithTranslatedNames = $this->productMeasurementUnitNameTranslator
            ->getProductMeasurementUnitTransfersWithTranslatedNames($productMeasurementUnitTransfers, $localeName);
        foreach ($productMeasurementUnitTransfersWithTranslatedNames as $productMeasurementUnitTransfer) {
            $productMeasurementUnitCode = $this->getProductMeasurementUnitCode($resource);
            if ($productMeasurementUnitCode === $productMeasurementUnitTransfer->getCode()) {
                $productMeasurementUnitRestResource = $this->productMeasurementUnitRestResponseBuilder
                    ->createProductMeasurementUnitRestResource($productMeasurementUnitTransfer);

                $resource->addRelationship($productMeasurementUnitRestResource);
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return string[]
     */
    protected function getAllCodes(array $resources): array
    {
        $codes = [];
        foreach ($resources as $resource) {
            $codes[$resource->getId()] = $this->getProductMeasurementUnitCode($resource);
        }

        return $codes;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return string
     */
    protected function getProductMeasurementUnitCode(RestResourceInterface $resource): string
    {
        /** @var \Generated\Shared\Transfer\RestSalesUnitsAttributesTransfer $restSalesUnitsAttributesTransfer */
        $restSalesUnitsAttributesTransfer = $resource->getAttributes();

        return $restSalesUnitsAttributesTransfer->getProductMeasurementUnitCode();
    }
}
