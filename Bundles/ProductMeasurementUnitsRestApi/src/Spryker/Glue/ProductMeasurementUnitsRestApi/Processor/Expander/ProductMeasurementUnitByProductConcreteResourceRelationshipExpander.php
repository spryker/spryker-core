<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\ProductMeasurementUnitRestResponseBuilderInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Translator\ProductMeasurementUnitNameTranslatorInterface;

class ProductMeasurementUnitByProductConcreteResourceRelationshipExpander implements ProductMeasurementUnitByProductConcreteResourceRelationshipExpanderInterface
{
    /**
     * @var string
     */
    protected const PRODUCT_CONCRETE_MAPPING_TYPE = 'sku';

    /**
     * @var \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\ProductMeasurementUnitRestResponseBuilderInterface
     */
    protected $productMeasurementUnitRestResponseBuilder;

    /**
     * @var \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

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
     * @param \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface $productMeasurementUnitStorageClient
     * @param \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Translator\ProductMeasurementUnitNameTranslatorInterface $productMeasurementUnitNameTranslator
     */
    public function __construct(
        ProductMeasurementUnitRestResponseBuilderInterface $productMeasurementUnitRestResponseBuilder,
        ProductMeasurementUnitsRestApiToProductStorageClientInterface $productStorageClient,
        ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface $productMeasurementUnitStorageClient,
        ProductMeasurementUnitNameTranslatorInterface $productMeasurementUnitNameTranslator
    ) {
        $this->productMeasurementUnitRestResponseBuilder = $productMeasurementUnitRestResponseBuilder;
        $this->productStorageClient = $productStorageClient;
        $this->productMeasurementUnitStorageClient = $productMeasurementUnitStorageClient;
        $this->productMeasurementUnitNameTranslator = $productMeasurementUnitNameTranslator;
    }

    /**
     * @param array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $productConcreteSkus = $this->getProductConcreteSkus($resources);
        $localeName = $restRequest->getMetadata()->getLocale();
        $productConcreteIds = $this->productStorageClient->getProductConcreteIdsByMapping(
            static::PRODUCT_CONCRETE_MAPPING_TYPE,
            $productConcreteSkus,
            $localeName,
        );

        $productMeasurementUnitTransfers = $this->productMeasurementUnitStorageClient
            ->getProductMeasurementBaseUnitsByProductConcreteIds($productConcreteIds);
        $productMeasurementUnitTransfersWithTranslatedNames = $this->productMeasurementUnitNameTranslator
            ->getProductMeasurementUnitTransfersWithTranslatedNames($productMeasurementUnitTransfers, $localeName);
        foreach ($resources as $resource) {
            $idProductConcrete = $productConcreteIds[$resource->getId()] ?? null;
            if (!$idProductConcrete || !isset($productMeasurementUnitTransfersWithTranslatedNames[$idProductConcrete])) {
                continue;
            }

            $restProductMeasurementUnitsResource = $this->productMeasurementUnitRestResponseBuilder->createProductMeasurementUnitRestResource(
                $productMeasurementUnitTransfersWithTranslatedNames[$idProductConcrete],
            );

            $resource->addRelationship($restProductMeasurementUnitsResource);
        }
    }

    /**
     * @param array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $resources
     *
     * @return array<string>
     */
    protected function getProductConcreteSkus(array $resources): array
    {
        $skus = [];
        foreach ($resources as $resource) {
            $skus[] = $resource->getId();
        }

        return $skus;
    }
}
