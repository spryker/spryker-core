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
use Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToStoreClientInterface;
use Spryker\Glue\ProductsRestApi\Processor\Mapper\ConcreteProductsResourceMapperInterface;
use Spryker\Glue\ProductsRestApi\Processor\ProductAttribute\ConcreteProductAttributeTranslationExpanderInterface;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ConcreteProductsReader implements ConcreteProductsReaderInterface
{
    protected const PRODUCT_CONCRETE_MAPPING_TYPE = 'sku';
    protected const KEY_ID_PRODUCT_CONCRETE = 'id_product_concrete';
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    protected const KEY_SKU = 'sku';
    protected const KEY_PRODUCT_ABSTRACT_SKU = 'product_abstract_sku';

    /**
     * @var \Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToStoreClientInterface
     */
    protected $storeClient;

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
     * @param \Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToStoreClientInterface $storeClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductsRestApi\Processor\Mapper\ConcreteProductsResourceMapperInterface $concreteProductsResourceMapper
     * @param \Spryker\Glue\ProductsRestApi\Processor\ProductAttribute\ConcreteProductAttributeTranslationExpanderInterface $concreteProductAttributeTranslationExpander
     * @param \Spryker\Glue\ProductsRestApiExtension\Dependency\Plugin\ConcreteProductsResourceExpanderPluginInterface[] $concreteProductsResourceExpanderPlugins
     */
    public function __construct(
        ProductsRestApiToProductStorageClientInterface $productStorageClient,
        ProductsRestApiToStoreClientInterface $storeClient,
        RestResourceBuilderInterface $restResourceBuilder,
        ConcreteProductsResourceMapperInterface $concreteProductsResourceMapper,
        ConcreteProductAttributeTranslationExpanderInterface $concreteProductAttributeTranslationExpander,
        array $concreteProductsResourceExpanderPlugins
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->storeClient = $storeClient;
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

        if (!$resourceIdentifier) {
            return $this->addConcreteSkuNotSpecifiedError($response);
        }

        $restResource = $this->findProductConcreteBySku($resourceIdentifier, $restRequest);

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
    public function getProductConcretesBySkus(array $productConcreteSkus, RestRequestInterface $restRequest): array
    {
        $productConcreteStorageData = $this->productStorageClient->getBulkProductConcreteStorageDataByMapping(
            static::PRODUCT_CONCRETE_MAPPING_TYPE,
            $productConcreteSkus,
            $restRequest->getMetadata()->getLocale()
        );

        return $this->createRestResourcesFromConcreteProductStorageData(
            $productConcreteStorageData,
            $restRequest
        );
    }

    /**
     * @param string $sku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findProductConcreteBySku(string $sku, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        $concreteProductData = $this->productStorageClient->findProductConcreteStorageDataByMapping(
            static::PRODUCT_CONCRETE_MAPPING_TYPE,
            $sku,
            $restRequest->getMetadata()->getLocale()
        );

        if (!$concreteProductData) {
            return null;
        }

        return current($this->createRestResourcesFromConcreteProductStorageData([$concreteProductData], $restRequest));
    }

    /**
     * @param int $idProductConcrete
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findProductConcreteById(int $idProductConcrete, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        $concreteProductData = $this->productStorageClient->findProductConcreteStorageData(
            $idProductConcrete,
            $restRequest->getMetadata()->getLocale()
        );

        if (!$concreteProductData) {
            return null;
        }

        return current($this->createRestResourcesFromConcreteProductStorageData([$concreteProductData], $restRequest));
    }

    /**
     * @param int[] $productConcreteIds
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getProductConcreteCollectionByIds(array $productConcreteIds, RestRequestInterface $restRequest): array
    {
        $bulkProductConcreteStorageData = $this->productStorageClient
            ->getBulkProductConcreteStorageData(
                $productConcreteIds,
                $restRequest->getMetadata()->getLocale()
            );

        return $this->createRestResourcesFromConcreteProductStorageData(
            $bulkProductConcreteStorageData,
            $restRequest
        );
    }

    /**
     * @param array $multipleProductConcreteStorageData
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    protected function createRestResourcesFromConcreteProductStorageData(
        array $multipleProductConcreteStorageData,
        RestRequestInterface $restRequest
    ): array {
        $concreteProductRestResources = [];

        foreach ($multipleProductConcreteStorageData as $productConcreteStorageData) {
            $restConcreteProductsAttributesTransfer = $this->concreteProductsResourceMapper
                ->mapConcreteProductsDataToConcreteProductsRestAttributes($productConcreteStorageData);

            $restConcreteProductsAttributesTransfer = $this->expandRestConcreteProductsAttributesTransfer(
                $restConcreteProductsAttributesTransfer,
                $productConcreteStorageData[static::KEY_ID_PRODUCT_CONCRETE],
                $restRequest
            );

            $concreteProductRestResources[$restConcreteProductsAttributesTransfer->getSku()] = $this->restResourceBuilder->createRestResource(
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $restConcreteProductsAttributesTransfer->getSku(),
                $restConcreteProductsAttributesTransfer
            );
        }

        return $this->expandWithProductAbstractSku(
            $concreteProductRestResources,
            $multipleProductConcreteStorageData,
            $restRequest->getMetadata()->getLocale()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer $concreteProductsRestAttributesTransfer
     * @param int $idProductConcrete
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer
     */
    protected function expandRestConcreteProductsAttributesTransfer(
        ConcreteProductsRestAttributesTransfer $concreteProductsRestAttributesTransfer,
        int $idProductConcrete,
        RestRequestInterface $restRequest
    ): ConcreteProductsRestAttributesTransfer {
        $concreteProductsRestAttributesTransfer = $this->concreteProductAttributeTranslationExpander
            ->addProductAttributeTranslation($concreteProductsRestAttributesTransfer, $restRequest->getMetadata()->getLocale());

        foreach ($this->concreteProductsResourceExpanderPlugins as $concreteProductsResourceExpanderPlugin) {
            $concreteProductsRestAttributesTransfer = $concreteProductsResourceExpanderPlugin->expand(
                $concreteProductsRestAttributesTransfer,
                $idProductConcrete,
                $restRequest
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

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $concreteProductRestResources
     * @param array $multipleProductConcreteStorageData
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    protected function expandWithProductAbstractSku(array $concreteProductRestResources, array $multipleProductConcreteStorageData, string $localeName): array
    {
        $productAbstractIds = [];
        foreach ($multipleProductConcreteStorageData as $productConcreteStorageData) {
            $productAbstractIds[] = $productConcreteStorageData[static::KEY_ID_PRODUCT_ABSTRACT];
        }

        $productAbstractSkus = $this->getProductAbstractSkus($productAbstractIds, $localeName);

        $expandedRestResources = [];
        foreach ($multipleProductConcreteStorageData as $productConcreteStorageData) {
            foreach ($concreteProductRestResources as $concreteProductRestResource) {
                if (
                    $productConcreteStorageData[static::KEY_SKU] !== $concreteProductRestResource->getId()
                    || !isset($productAbstractSkus[$productConcreteStorageData[static::KEY_ID_PRODUCT_ABSTRACT]])
                ) {
                    $expandedRestResources[$productConcreteStorageData[static::KEY_SKU]] = $concreteProductRestResource;

                    continue;
                }

                $concreteProductRestResource->getAttributes()->offsetSet(
                    ConcreteProductsRestAttributesTransfer::PRODUCT_ABSTRACT_SKU,
                    $productAbstractSkus[$productConcreteStorageData[static::KEY_ID_PRODUCT_ABSTRACT]]
                );

                $expandedRestResources[$productConcreteStorageData[static::KEY_SKU]] = $concreteProductRestResource;
            }
        }

        return $expandedRestResources;
    }

    /**
     * @param int[] $productAbstractIds
     * @param string $localeName
     *
     * @return string[]
     */
    protected function getProductAbstractSkus(array $productAbstractIds, string $localeName): array
    {
        $productAbstractData = $this->productStorageClient
            ->getBulkProductAbstractStorageDataByProductAbstractIdsForLocaleNameAndStore(
                $productAbstractIds,
                $localeName,
                $this->storeClient->getCurrentStore()->getName()
            );

        $productAbstractSkus = [];
        foreach ($productAbstractData as $productAbstractDatum) {
            $productAbstractSkus[$productAbstractDatum[static::KEY_ID_PRODUCT_ABSTRACT]] = $productAbstractDatum[static::KEY_SKU];
        }

        return $productAbstractSkus;
    }
}
