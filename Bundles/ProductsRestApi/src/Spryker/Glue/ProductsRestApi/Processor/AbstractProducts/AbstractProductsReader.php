<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Processor\AbstractProducts;

use Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductsRestApi\Processor\ConcreteProducts\ConcreteProductsReaderInterface;
use Spryker\Glue\ProductsRestApi\Processor\Mapper\AbstractProductsResourceMapperInterface;
use Spryker\Glue\ProductsRestApi\Processor\ProductAttribute\AbstractProductAttributeTranslationExpanderInterface;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class AbstractProductsReader implements AbstractProductsReaderInterface
{
    protected const PRODUCT_CONCRETE_IDS_KEY = 'product_concrete_ids';
    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';
    protected const KEY_SKU = 'sku';
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var \Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductsRestApi\Processor\Mapper\AbstractProductsResourceMapperInterface
     */
    protected $abstractProductsResourceMapper;

    /**
     * @var \Spryker\Glue\ProductsRestApi\Processor\ConcreteProducts\ConcreteProductsReaderInterface
     */
    protected $concreteProductsReader;

    /**
     * @var \Spryker\Glue\ProductsRestApi\ProductsRestApiConfig
     */
    protected $productsRestApiConfig;

    /**
     * @var \Spryker\Glue\ProductsRestApi\Processor\ProductAttribute\AbstractProductAttributeTranslationExpanderInterface
     */
    protected $abstractProductAttributeTranslationExpander;

    /**
     * @var \Spryker\Glue\ProductsRestApiExtension\Dependency\Plugin\AbstractProductsResourceExpanderPluginInterface[]
     */
    protected $abstractProductsResourceExpanderPlugins;

    /**
     * @param \Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductsRestApi\Processor\Mapper\AbstractProductsResourceMapperInterface $abstractProductsResourceMapper
     * @param \Spryker\Glue\ProductsRestApi\Processor\ConcreteProducts\ConcreteProductsReaderInterface $concreteProductsReader
     * @param \Spryker\Glue\ProductsRestApi\ProductsRestApiConfig $productsRestApiConfig
     * @param \Spryker\Glue\ProductsRestApi\Processor\ProductAttribute\AbstractProductAttributeTranslationExpanderInterface $abstractProductAttributeTranslationExpander
     * @param \Spryker\Glue\ProductsRestApiExtension\Dependency\Plugin\AbstractProductsResourceExpanderPluginInterface[] $abstractProductsResourceExpanderPlugins
     */
    public function __construct(
        ProductsRestApiToProductStorageClientInterface $productStorageClient,
        RestResourceBuilderInterface $restResourceBuilder,
        AbstractProductsResourceMapperInterface $abstractProductsResourceMapper,
        ConcreteProductsReaderInterface $concreteProductsReader,
        ProductsRestApiConfig $productsRestApiConfig,
        AbstractProductAttributeTranslationExpanderInterface $abstractProductAttributeTranslationExpander,
        array $abstractProductsResourceExpanderPlugins
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->abstractProductsResourceMapper = $abstractProductsResourceMapper;
        $this->concreteProductsReader = $concreteProductsReader;
        $this->productsRestApiConfig = $productsRestApiConfig;
        $this->abstractProductAttributeTranslationExpander = $abstractProductAttributeTranslationExpander;
        $this->abstractProductsResourceExpanderPlugins = $abstractProductsResourceExpanderPlugins;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getProductAbstractStorageData(RestRequestInterface $restRequest): RestResponseInterface
    {
        $response = $this->restResourceBuilder->createRestResponse();

        $resourceIdentifier = $restRequest->getResource()->getId();

        if (!$resourceIdentifier) {
            return $this->addAbstractSkuNotSpecifiedError($response);
        }

        $restResource = $this->findProductAbstractBySku($resourceIdentifier, $restRequest);

        if (!$restResource) {
            return $this->addAbstractProductNotFoundError($response);
        }

        $restResource = $this->addConcreteProducts($restResource, $restRequest);

        return $response->addResource($restResource);
    }

    /**
     * @param string[] $skus
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getProductAbstractsBySkus(array $skus, RestRequestInterface $restRequest): array
    {
        $localeName = $restRequest->getMetadata()->getLocale();
        $abstractProductCollection = $this->productStorageClient
            ->findBulkProductAbstractStorageDataByMapping(
                static::PRODUCT_ABSTRACT_MAPPING_TYPE,
                $skus,
                $localeName
            );

        if (!$abstractProductCollection) {
            return [];
        }

        return $this->createRestResourcesFromAbstractProductStorageData($abstractProductCollection, $localeName);
    }

    /**
     * @param string $sku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findProductAbstractBySku(string $sku, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        $localeName = $restRequest->getMetadata()->getLocale();
        $productAbstractData = $this->productStorageClient
            ->findProductAbstractStorageDataByMapping(
                static::PRODUCT_ABSTRACT_MAPPING_TYPE,
                $sku,
                $localeName
            );

        if (!$productAbstractData) {
            return null;
        }

        return $this->createRestResourceFromAbstractProductStorageData($productAbstractData, $localeName);
    }

    /**
     * @param int $idProductAbstract
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findProductAbstractById(int $idProductAbstract, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        $localeName = $restRequest->getMetadata()->getLocale();
        $productAbstractData = $this->productStorageClient
            ->findProductAbstractStorageData($idProductAbstract, $localeName);

        if (!$productAbstractData) {
            return null;
        }

        return $this->createRestResourceFromAbstractProductStorageData($productAbstractData, $localeName);
    }

    /**
     * @param int[] $productAbstractIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getProductAbstractsByIds(array $productAbstractIds, string $localeName, string $storeName): array
    {
        $abstractProductCollection = $this->productStorageClient
            ->getBulkProductAbstractStorageDataByProductAbstractIdsForLocaleNameAndStore(
                $productAbstractIds,
                $localeName,
                $storeName
            );

        if (!$abstractProductCollection) {
            return [];
        }

        return $this->createRestResourcesFromAbstractProductStorageData($abstractProductCollection, $localeName);
    }

    /**
     * @param array $productAbstractData
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createRestResourceFromAbstractProductStorageData(array $productAbstractData, string $localeName): RestResourceInterface
    {
        $restAbstractProductsAttributesTransfer = $this->abstractProductsResourceMapper
            ->mapAbstractProductsDataToAbstractProductsRestAttributes($productAbstractData);
        $restAbstractProductsAttributesTransfer = $this->expandRestAbstractProductsAttributesTransfer(
            $restAbstractProductsAttributesTransfer,
            $productAbstractData[static::KEY_ID_PRODUCT_ABSTRACT],
            $localeName
        );
        $restAbstractProductsAttributesTransfer = $this->abstractProductAttributeTranslationExpander
            ->addProductAttributeTranslation($restAbstractProductsAttributesTransfer, $localeName);

        return $this->restResourceBuilder->createRestResource(
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            $restAbstractProductsAttributesTransfer->getSku(),
            $restAbstractProductsAttributesTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer
     */
    protected function expandRestAbstractProductsAttributesTransfer(
        AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer,
        int $idProductAbstract,
        string $localeName
    ): AbstractProductsRestAttributesTransfer {
        foreach ($this->abstractProductsResourceExpanderPlugins as $abstractProductsResourceExpanderPlugin) {
            $abstractProductsRestAttributesTransfer = $abstractProductsResourceExpanderPlugin->expand(
                $abstractProductsRestAttributesTransfer,
                $idProductAbstract,
                $localeName
            );
        }

        return $abstractProductsRestAttributesTransfer;
    }

    /**
     * @param array $abstractProductData
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    protected function createRestResourcesFromAbstractProductStorageData(array $abstractProductData, string $localeName): array
    {
        $restResources = [];

        foreach ($abstractProductData as $abstractProductDataItem) {
            $restResources[$abstractProductDataItem[static::KEY_SKU]] =
                $this->createRestResourceFromAbstractProductStorageData($abstractProductDataItem, $localeName);
        }

        return $restResources;
    }

    /**
     * @deprecated Will be removed in the next major version.
     *
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function addConcreteProducts(RestResourceInterface $restResource, RestRequestInterface $restRequest): RestResourceInterface
    {
        if (!$this->productsRestApiConfig->getAllowedProductConcreteEagerRelationship()) {
            return $restResource;
        }

        /** @var \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer $attributes */
        $attributes = $restResource->getAttributes();
        $concreteProductsResourceList = $this->concreteProductsReader
            ->getProductConcretesBySkus(
                $attributes->getAttributeMap()[static::PRODUCT_CONCRETE_IDS_KEY],
                $restRequest
            );

        foreach ($concreteProductsResourceList as $concreteProductResource) {
            $restResource->addRelationship($concreteProductResource);
        }

        return $restResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $response
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addAbstractSkuNotSpecifiedError(RestResponseInterface $response): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED);

        return $response->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $response
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addAbstractProductNotFoundError(RestResponseInterface $response): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_CANT_FIND_ABSTRACT_PRODUCT)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_ABSTRACT_PRODUCT);

        return $response->addError($restErrorTransfer);
    }
}
