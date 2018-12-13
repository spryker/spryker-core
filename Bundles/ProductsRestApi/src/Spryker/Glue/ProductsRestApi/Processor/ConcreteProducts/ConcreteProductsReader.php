<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Processor\ConcreteProducts;

use Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductsRestApi\Processor\Mapper\ConcreteProductsResourceMapperInterface;
use Spryker\Glue\ProductsRestApi\Processor\ProductAttribute\ConcreteProductAttributeTranslationExpanderInterface;
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
     * @var \Spryker\Glue\ProductsRestApi\Processor\ProductAttribute\ConcreteProductAttributeTranslationExpanderInterface
     */
    protected $concreteProductAttributeTranslationExpander;

    /**
     * @var \Spryker\Glue\ProductsRestApiExtension\Dependency\Plugin\ConcreteProductsResourceExpanderPluginInterface[]
     */
    protected $concreteProductsResourceExpanderPlugins;

    /**
     * @param \Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductsRestApi\Processor\Mapper\ConcreteProductsResourceMapperInterface $concreteProductsResourceMapper
     * @param \Spryker\Glue\ProductsRestApi\Processor\ProductAttribute\ConcreteProductAttributeTranslationExpanderInterface $concreteProductAttributeTranslationExpander
     * @param \Spryker\Glue\ProductsRestApiExtension\Dependency\Plugin\ConcreteProductsResourceExpanderPluginInterface[] $concreteProductsResourceExpanderPlugins
     */
    public function __construct(
        ProductsRestApiToProductStorageClientInterface $productStorageClient,
        RestResourceBuilderInterface $restResourceBuilder,
        ConcreteProductsResourceMapperInterface $concreteProductsResourceMapper,
        ConcreteProductAttributeTranslationExpanderInterface $concreteProductAttributeTranslationExpander,
        array $concreteProductsResourceExpanderPlugins
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->concreteProductsResourceMapper = $concreteProductsResourceMapper;
        $this->concreteProductAttributeTranslationExpander = $concreteProductAttributeTranslationExpander;
        $this->concreteProductsResourceExpanderPlugins = $concreteProductsResourceExpanderPlugins;
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
            return $this->addConcreteSkuNotSpecifiedError($response);
        }

        $restResource = $this->findOneByProductConcrete($resourceIdentifier, $restRequest);

        if (!$restResource) {
            return $this->addConcreteProductNotFoundError($response);
        }

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

        $restConcreteProductsAttributesTransfer = $this->concreteProductsResourceMapper
            ->mapConcreteProductsDataToConcreteProductsRestAttributes($concreteProductData);
        $restConcreteProductsAttributesTransfer = $this->concreteProductAttributeTranslationExpander
            ->addProductAttributeTranslation($restConcreteProductsAttributesTransfer, $restRequest->getMetadata()->getLocale());
        $restConcreteProductsAttributesTransfer = $this->expandConcreteProduct(
            $restConcreteProductsAttributesTransfer,
            $sku,
            $restRequest->getMetadata()->getLocale()
        );

        return $this->restResourceBuilder->createRestResource(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $sku,
            $restConcreteProductsAttributesTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer $concreteProductsRestAttributesTransfer
     * @param string $concreteProductSku
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer
     */
    protected function expandConcreteProduct(
        ConcreteProductsRestAttributesTransfer $concreteProductsRestAttributesTransfer,
        string $concreteProductSku,
        string $localeName
    ): ConcreteProductsRestAttributesTransfer {
        $concreteProductsRestAttributesTransfer = $this->concreteProductAttributeTranslationExpander
            ->addProductAttributeTranslation($concreteProductsRestAttributesTransfer, $localeName);

        $restConcreteProductsAttributesTransfer = $this->executeExpanderPlugins(
            $concreteProductsRestAttributesTransfer,
            $concreteProductSku,
            $localeName
        );

        return $restConcreteProductsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer $concreteProductsRestAttributesTransfer
     * @param string $concreteProductSku
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer
     */
    protected function executeExpanderPlugins(
        ConcreteProductsRestAttributesTransfer $concreteProductsRestAttributesTransfer,
        string $concreteProductSku,
        string $localeName
    ): ConcreteProductsRestAttributesTransfer {
        foreach ($this->concreteProductsResourceExpanderPlugins as $concreteProductsResourceExpanderPlugin) {
            $concreteProductsRestAttributesTransfer = $concreteProductsResourceExpanderPlugin->expand(
                $concreteProductsRestAttributesTransfer,
                $concreteProductSku,
                $localeName
            );
        }

        return $concreteProductsRestAttributesTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $response
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addConcreteSkuNotSpecifiedError(RestResponseInterface $response): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED);

        return $response->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $response
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addConcreteProductNotFoundError(RestResponseInterface $response): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_CANT_FIND_CONCRETE_PRODUCT)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_CONCRETE_PRODUCT);

        return $response->addError($restErrorTransfer);
    }
}
