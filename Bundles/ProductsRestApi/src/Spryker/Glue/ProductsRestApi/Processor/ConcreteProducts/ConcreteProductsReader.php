<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Processor\ConcreteProducts;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductsRestApi\Processor\Mapper\ConcreteProductsResourceMapperInterface;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ConcreteProductsReader implements ConcreteProductsReaderInterface
{
    protected const PRODUCT_CONCRETE_MAPPING_TYPE = 'sku';

    /**
     * @var \Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductsRestApi\Processor\Mapper\ConcreteProductsResourceMapperInterface
     */
    protected $concreteProductsResourceMapper;

    /**
     * @param \Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductsRestApi\Processor\Mapper\ConcreteProductsResourceMapperInterface $concreteProductsResourceMapper
     */
    public function __construct(
        ProductsRestApiToProductStorageClientInterface $productStorageClient,
        RestResourceBuilderInterface $restResourceBuilder,
        ConcreteProductsResourceMapperInterface $concreteProductsResourceMapper
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->concreteProductsResourceMapper = $concreteProductsResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getProductConcreteStorageData(RestRequestInterface $restRequest): RestResponseInterface
    {
        $response = $this->restResourceBuilder->createRestResponse();

        $resourceIdentifier = $restRequest->getResource()->getId();

        if (empty($resourceIdentifier)) {
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setStatus(Response::HTTP_BAD_REQUEST);
            return $response->addError($restErrorTransfer);
        }

        $concreteProductData = $this->productStorageClient
            ->findProductConcreteStorageDataByMapping(
                static::PRODUCT_CONCRETE_MAPPING_TYPE,
                $resourceIdentifier,
                $restRequest->getMetadata()->getLocale()
            );

        if (!$concreteProductData) {
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setCode(ProductsRestApiConfig::RESPONSE_CODE_CANT_FIND_CONCRETE_PRODUCT)
                ->setStatus(Response::HTTP_NOT_FOUND)
                ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_CONCRETE_PRODUCT);

            return $response->addError($restErrorTransfer);
        }

        $restConcreteProductsAttributesTransfer = $this->concreteProductsResourceMapper
            ->mapConcreteProductsDataToConcreteProductsRestAttributes($concreteProductData);

        $restResource = $this->restResourceBuilder->createRestResource(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $concreteProductData[static::PRODUCT_CONCRETE_MAPPING_TYPE],
            $restConcreteProductsAttributesTransfer
        );

        return $response->addResource($restResource);
    }

    /**
     * @param string[] $productConcreteSkus
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function findProductConcretesByProductConcreteSkus(array $productConcreteSkus, RestRequestInterface $restRequest): array
    {
        $results = [];
        foreach ($productConcreteSkus as $idResource) {
            $productResource = $this->findOneByProductConcrete($idResource, $restRequest);
            if (!$productResource) {
                continue;
            }
            $results[] = $productResource;
        }

        return $results;
    }

    /**
     * @param string $sku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findOneByProductConcrete(string $sku, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        $concreteProductData = $this->productStorageClient->findProductConcreteStorageDataByMapping(
            static::PRODUCT_CONCRETE_MAPPING_TYPE,
            $sku,
            $restRequest->getMetadata()->getLocale()
        );

        if (!$concreteProductData) {
            return null;
        }

        return $this->restResourceBuilder->createRestResource(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $sku,
            $this->concreteProductsResourceMapper->mapConcreteProductsDataToConcreteProductsRestAttributes($concreteProductData)
        );
    }
}
