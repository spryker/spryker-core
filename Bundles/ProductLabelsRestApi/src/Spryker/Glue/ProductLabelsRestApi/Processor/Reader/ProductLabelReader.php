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
use Spryker\Glue\ProductLabelsRestApi\Processor\Mapper\ProductLabelMapperInterface;
use Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ProductLabelReader implements ProductLabelReaderInterface
{
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
     * @param \Spryker\Glue\ProductLabelsRestApi\Dependency\Client\ProductLabelsRestApiToProductLabelStorageClientInterface $productLabelStorageClient
     * @param \Spryker\Glue\ProductLabelsRestApi\Processor\Mapper\ProductLabelMapperInterface $productLabelMapper
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        ProductLabelsRestApiToProductLabelStorageClientInterface $productLabelStorageClient,
        ProductLabelMapperInterface $productLabelMapper,
        RestResourceBuilderInterface $restResourceBuilder
    )
    {
        $this->productLabelStorageClient = $productLabelStorageClient;
        $this->productLabelMapper = $productLabelMapper;
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function findByKey(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        if (!$restRequest->getResource()->getId()) {
            return $this->createProductLabelNotFoundError($restResponse);
        }

        $labelTransfer = $this->productLabelStorageClient->findLabelByKey(
            $restRequest->getResource()->getId(),
            $restRequest->getMetadata()->getLocale()
        );

        if (!$labelTransfer) {
            return $this->createProductLabelNotFoundError($restResponse);
        }

        $restProductLabelAttributesTransfer = $this
            ->productLabelMapper
            ->mapProductLabelDictionaryItemTransferToRestProductLabelsAttributesTransfer($labelTransfer);

        $restResource = $this->restResourceBuilder->createRestResource(
            ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            $restRequest->getResource()->getId(),
            $restProductLabelAttributesTransfer
        );

        return $restResponse->addResource($restResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createProductLabelNotFoundError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductLabelsRestApiConfig::RESPONSE_CODE_CANT_FIND_PRODUCT_LABEL)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductLabelsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_PRODUCT_LABEL);

        return $restResponse->addError($restErrorTransfer);
    }
}
