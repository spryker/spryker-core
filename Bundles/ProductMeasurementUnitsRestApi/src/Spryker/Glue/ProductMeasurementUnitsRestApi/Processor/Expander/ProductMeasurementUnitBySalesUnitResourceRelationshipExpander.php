<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander;

use Generated\Shared\Transfer\RestSalesUnitsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\ProductMeasurementUnitRestResponseBuilderInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Translator\ProductMeasurementUnitNameTranslatorInterface;

class ProductMeasurementUnitBySalesUnitResourceRelationshipExpander implements ProductMeasurementUnitBySalesUnitResourceRelationshipExpanderInterface
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
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $productMeasurementUnitCodes = $this->getProductMeasurementUnitCodes($resources);
        $productMeasurementUnitTransfers = $this->productMeasurementUnitStorageClient
            ->getProductMeasurementUnitsByMapping(
                static::PRODUCT_MEASUREMENT_UNIT_MAPPING_TYPE,
                $productMeasurementUnitCodes
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
            $productMeasurementUnitCode = $this->findProductMeasurementUnitCode($resource);
            if ($productMeasurementUnitCode !== $productMeasurementUnitTransfer->getCode()) {
                continue;
            }

            $productMeasurementUnitRestResource = $this->productMeasurementUnitRestResponseBuilder
                ->createProductMeasurementUnitRestResource($productMeasurementUnitTransfer);

            $resource->addRelationship($productMeasurementUnitRestResource);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return string[]
     */
    protected function getProductMeasurementUnitCodes(array $resources): array
    {
        $productMeasurementUnitCodes = [];
        foreach ($resources as $resource) {
            $productMeasurementUnitCode = $this->findProductMeasurementUnitCode($resource);
            if (!$productMeasurementUnitCode) {
                continue;
            }

            $productMeasurementUnitCodes[$resource->getId()] = $productMeasurementUnitCode;
        }

        return $productMeasurementUnitCodes;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return string|null
     */
    protected function findProductMeasurementUnitCode(RestResourceInterface $resource): ?string
    {
        $restSalesUnitsAttributesTransfer = $resource->getAttributes();
        if (!$restSalesUnitsAttributesTransfer instanceof RestSalesUnitsAttributesTransfer) {
            return null;
        }

        return $restSalesUnitsAttributesTransfer->getProductMeasurementUnitCode();
    }
}
