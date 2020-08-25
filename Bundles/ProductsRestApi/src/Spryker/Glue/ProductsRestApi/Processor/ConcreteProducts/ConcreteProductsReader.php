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

        $productAbstractIds = [];
        foreach ($productConcreteStorageData as $productConcreteStorageDatum) {
            if (!isset($productConcreteStorageDatum[static::KEY_ID_PRODUCT_ABSTRACT])) {
                continue;
            }

            $productAbstractIds[] = $productConcreteStorageDatum[static::KEY_ID_PRODUCT_ABSTRACT];
        }

        $productAbstractSkus = $this->getProductAbstractSkus($productAbstractIds, $restRequest);

        $concreteProductRestResources = [];
        foreach ($productConcreteStorageData as $productConcreteStorageDatum) {
            if (!isset($productConcreteStorageDatum[static::KEY_SKU])) {
                continue;
            }

            $productConcreteStorageDatum[static::KEY_PRODUCT_ABSTRACT_SKU] = $productAbstractSkus[$productConcreteStorageDatum[static::KEY_ID_PRODUCT_ABSTRACT]];

            $concreteProductRestResources[$productConcreteStorageDatum[static::KEY_SKU]] = $this->createRestResourceFromConcreteProductStorageData(
                $productConcreteStorageDatum,
                $restRequest
            );
        }

        return $concreteProductRestResources;
    }

    /**
     * @deprecated Use {@link findProductConcreteBySku()} instead.
     *
     * @param string $sku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findOneByProductConcrete(string $sku, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        return $this->findProductConcreteBySku($sku, $restRequest);
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

        $productAbstractSkus = $this->getProductAbstractSkus(
            [$concreteProductData[static::KEY_ID_PRODUCT_ABSTRACT]],
            $restRequest
        );
        $concreteProductData[static::KEY_PRODUCT_ABSTRACT_SKU] = current($productAbstractSkus);

        return $this->createRestResourceFromConcreteProductStorageData($concreteProductData, $restRequest);
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

        $productAbstractSkus = $this->getProductAbstractSkus(
            [$concreteProductData[static::KEY_ID_PRODUCT_ABSTRACT]],
            $restRequest
        );
        $concreteProductData[static::KEY_PRODUCT_ABSTRACT_SKU] = current($productAbstractSkus);

        return $this->createRestResourceFromConcreteProductStorageData($concreteProductData, $restRequest);
    }

    /**
     * @param array $concreteProductData
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createRestResourceFromConcreteProductStorageData(
        array $concreteProductData,
        RestRequestInterface $restRequest
    ): RestResourceInterface {
        $restConcreteProductsAttributesTransfer = $this->concreteProductsResourceMapper
            ->mapConcreteProductsDataToConcreteProductsRestAttributes($concreteProductData);

        $restConcreteProductsAttributesTransfer = $this->expandRestConcreteProductsAttributesTransfer(
            $restConcreteProductsAttributesTransfer,
            $concreteProductData[static::KEY_ID_PRODUCT_CONCRETE],
            $restRequest
        );

        return $this->restResourceBuilder->createRestResource(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $restConcreteProductsAttributesTransfer->getSku(),
            $restConcreteProductsAttributesTransfer
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
     * @param int[] $productAbstractIds
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return string[]
     */
    protected function getProductAbstractSkus(array $productAbstractIds, RestRequestInterface $restRequest): array
    {
        $productAbstractData = $this->productStorageClient
            ->getBulkProductAbstractStorageDataByProductAbstractIdsForLocaleNameAndStore(
                $productAbstractIds,
                $restRequest->getMetadata()->getLocale(),
                $this->storeClient->getCurrentStore()->getName()
            );

        $productAbstractSkus = [];
        foreach ($productAbstractData as $productAbstractDatum) {
            $productAbstractSkus[$productAbstractDatum[static::KEY_ID_PRODUCT_ABSTRACT]] = $productAbstractDatum[static::KEY_SKU];
        }

        return $productAbstractSkus;
    }
}
