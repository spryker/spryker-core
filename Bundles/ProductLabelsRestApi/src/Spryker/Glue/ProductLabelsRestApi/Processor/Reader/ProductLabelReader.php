<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductLabelsRestApi\Processor\Reader;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestProductLabelsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductLabelsRestApi\Dependency\Client\ProductLabelsRestApiToProductLabelStorageClientInterface;
use Spryker\Glue\ProductLabelsRestApi\Dependency\Client\ProductLabelsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductLabelsRestApi\Processor\Mapper\ProductLabelMapperInterface;
use Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ProductLabelReader implements ProductLabelReaderInterface
{
    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';
    protected const PRODUCT_CONCRETE_MAPPING_TYPE = 'sku';
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    protected const KEY_ID_PRODUCT_CONCRETE = 'id_product_concrete';

    /**
     * @var \Spryker\Glue\ProductLabelsRestApi\Dependency\Client\ProductLabelsRestApiToProductLabelStorageClientInterface
     */
    protected $productLabelStorageClient;

    /**
     * @var \Spryker\Glue\ProductLabelsRestApi\Processor\Mapper\ProductLabelMapperInterface
     */
    protected $productLabelMapper;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductLabelsRestApi\Dependency\Client\ProductLabelsRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \Spryker\Glue\ProductLabelsRestApi\Dependency\Client\ProductLabelsRestApiToProductLabelStorageClientInterface $productLabelStorageClient
     * @param \Spryker\Glue\ProductLabelsRestApi\Processor\Mapper\ProductLabelMapperInterface $productLabelMapper
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductLabelsRestApi\Dependency\Client\ProductLabelsRestApiToProductStorageClientInterface $productStorageClient
     */
    public function __construct(
        ProductLabelsRestApiToProductLabelStorageClientInterface $productLabelStorageClient,
        ProductLabelMapperInterface $productLabelMapper,
        RestResourceBuilderInterface $restResourceBuilder,
        ProductLabelsRestApiToProductStorageClientInterface $productStorageClient
    ) {
        $this->productLabelStorageClient = $productLabelStorageClient;
        $this->productLabelMapper = $productLabelMapper;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function findById(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        if (!$restRequest->getResource()->getId()) {
            return $this->addProductLabelMissingErrorToResponse($restResponse);
        }

        $labelTransfers = $this->productLabelStorageClient->findLabels(
            [$restRequest->getResource()->getId()],
            $restRequest->getMetadata()->getLocale()
        );

        if (!count($labelTransfers)) {
            return $this->addProductLabelNotFoundErrorToResponse($restResponse);
        }

        $restProductLabelAttributesTransfer = $this
            ->productLabelMapper
            ->mapProductLabelDictionaryItemTransferToRestProductLabelsAttributesTransfer(
                reset($labelTransfers),
                new RestProductLabelsAttributesTransfer()
            );

        $restResource = $this->restResourceBuilder->createRestResource(
            ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            $restRequest->getResource()->getId(),
            $restProductLabelAttributesTransfer
        );

        return $restResponse->addResource($restResource);
    }

    /**
     * @param string $sku
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function findByAbstractSku(string $sku, string $localeName): array
    {
        $abstractProductData = $this->productStorageClient->findProductAbstractStorageDataByMapping(
            static::PRODUCT_ABSTRACT_MAPPING_TYPE,
            $sku,
            $localeName
        );
        if (!$abstractProductData) {
            return [];
        }

        $productLabels = $this->productLabelStorageClient->findLabelsByIdProductAbstract(
            $abstractProductData[static::KEY_ID_PRODUCT_ABSTRACT],
            $localeName
        );

        return $this->prepareRestResourceCollection($productLabels);
    }

    /**
     * @param string[] $concreteSkuList
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][]
     */
    public function findLabelByConcreteProductSkuList(array $concreteSkuList, string $localeName): array
    {
        $concreteProductDataList = $this->productStorageClient->getProductConcreteStorageDataByMappingAndIdentifiers(
            static::PRODUCT_CONCRETE_MAPPING_TYPE,
            $concreteSkuList,
            $localeName
        );
        $abstractProductIdsByProductConcreteSku = $this->mapConcreteProductDataListToAbstractProductIds($concreteProductDataList);
        $productLabelsList = $this->productLabelStorageClient->getLabelsByProductAbstractIds(
            array_unique($abstractProductIdsByProductConcreteSku),
            $localeName
        );
        $restResourceCollections = array_map(function ($productLabels) {
            return $this->prepareRestResourceCollection($productLabels);
        }, $productLabelsList);
        $restResourceCollectionsByProductConcreteSku = [];

        foreach ($abstractProductIdsByProductConcreteSku as $productConcreteSku => $idProductAbstract) {
            $restResourceCollectionsByProductConcreteSku[$productConcreteSku] = $restResourceCollections[$idProductAbstract] ?? [];
        }

        return $restResourceCollectionsByProductConcreteSku;
    }

    /**
     * @param array $concreteProductDataList
     *
     * @return int[]
     */
    protected function mapConcreteProductDataListToAbstractProductIds(array $concreteProductDataList): array
    {
        $abstractProductIds = [];

        foreach ($concreteProductDataList as $concreteProductData) {
            $abstractProductIds[$concreteProductData[static::PRODUCT_CONCRETE_MAPPING_TYPE]] =
                $concreteProductData[static::KEY_ID_PRODUCT_ABSTRACT];
        }

        return $abstractProductIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[] $productLabels
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    protected function prepareRestResourceCollection(array $productLabels): array
    {
        $productLabelResources = [];

        foreach ($productLabels as $productLabel) {
            $restProductLabelAttributesTransfer = $this
                ->productLabelMapper
                ->mapProductLabelDictionaryItemTransferToRestProductLabelsAttributesTransfer(
                    $productLabel,
                    new RestProductLabelsAttributesTransfer()
                );

            $productLabelResources[] = $this->restResourceBuilder->createRestResource(
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                (string)$productLabel->getIdProductLabel(),
                $restProductLabelAttributesTransfer
            );
        }

        return $productLabelResources;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addProductLabelNotFoundErrorToResponse(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductLabelsRestApiConfig::RESPONSE_CODE_CANT_FIND_PRODUCT_LABEL)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductLabelsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_PRODUCT_LABEL);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addProductLabelMissingErrorToResponse(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductLabelsRestApiConfig::RESPONSE_CODE_PRODUCT_LABEL_ID_IS_MISSING)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ProductLabelsRestApiConfig::RESPONSE_DETAIL_PRODUCT_LABEL_ID_IS_MISSING);

        return $restResponse->addError($restErrorTransfer);
    }
}
