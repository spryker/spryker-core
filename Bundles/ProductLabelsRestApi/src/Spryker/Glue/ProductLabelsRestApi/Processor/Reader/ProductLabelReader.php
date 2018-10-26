<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductLabelsRestApi\Processor\Reader;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
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
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

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

        if (count($labelTransfers) < 1) {
            return $this->addProductLabelNotFoundErrorToRestResponse($restResponse);
        }
        $labelTransfer = $labelTransfers[0];

        $restProductLabelAttributesTransfer = $this
            ->productLabelMapper
            ->mapProductLabelDictionaryItemTransferToRestProductLabelsAttributesTransfer($labelTransfer);

        $restResource = $this->restResourceBuilder->createRestResource(
            ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            (string)$labelTransfer->getIdProductLabel(),
            $restProductLabelAttributesTransfer
        );

        return $restResponse->addResource($restResource);
    }

    /**
     * @param string $sku
     * @param string $locale
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function findByAbstractSku(string $sku, string $locale): array
    {
        $abstractProductData = $this->productStorageClient->findProductAbstractStorageDataByMapping(
            static::PRODUCT_ABSTRACT_MAPPING_TYPE,
            $sku,
            $locale
        );

        $productLabels = $this->productLabelStorageClient->findLabelsByIdProductAbstract(
            $abstractProductData[static::KEY_ID_PRODUCT_ABSTRACT],
            $locale
        );

        return $this->mapProductLabelDictionaryItemTransfersToRestResources($productLabels);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[] $productLabels
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    protected function mapProductLabelDictionaryItemTransfersToRestResources(array $productLabels): array
    {
        $productLabelResources = [];

        foreach ($productLabels as $productLabel) {
            $restProductLabelAttributesTransfer = $this
                ->productLabelMapper
                ->mapProductLabelDictionaryItemTransferToRestProductLabelsAttributesTransfer($productLabel);

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
    protected function addProductLabelNotFoundErrorToRestResponse(RestResponseInterface $restResponse): RestResponseInterface
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
