<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductTaxSetsRestApi\Processor\ProductTaxSet;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestProductTaxSetsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductTaxSetsRestApi\Dependency\Client\ProductTaxSetsRestApiToTaxProductStorageClientInterface;
use Spryker\Glue\ProductTaxSetsRestApi\Dependency\Client\ProductTaxSetsRestApiToTaxStorageClientInterface;
use Spryker\Glue\ProductTaxSetsRestApi\Processor\Mapper\ProductTaxSetResourceMapperInterface;
use Spryker\Glue\ProductTaxSetsRestApi\ProductTaxSetsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ProductTaxSetReader implements ProductTaxSetReaderInterface
{
    /**
     * @var \Spryker\Glue\ProductTaxSetsRestApi\Dependency\Client\ProductTaxSetsRestApiToTaxProductStorageClientInterface
     */
    protected $taxProductStorageClient;

    /**
     * @var \Spryker\Glue\ProductTaxSetsRestApi\Dependency\Client\ProductTaxSetsRestApiToTaxStorageClientInterface
     */
    protected $taxStorageClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductTaxSetsRestApi\Processor\Mapper\ProductTaxSetResourceMapperInterface
     */
    protected $productTaxSetsResourceMapper;

    /**
     * @param \Spryker\Glue\ProductTaxSetsRestApi\Dependency\Client\ProductTaxSetsRestApiToTaxProductStorageClientInterface $taxProductStorageClient
     * @param \Spryker\Glue\ProductTaxSetsRestApi\Dependency\Client\ProductTaxSetsRestApiToTaxStorageClientInterface $taxStorageClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductTaxSetsRestApi\Processor\Mapper\ProductTaxSetResourceMapperInterface $productTaxSetsResourceMapper
     */
    public function __construct(
        ProductTaxSetsRestApiToTaxProductStorageClientInterface $taxProductStorageClient,
        ProductTaxSetsRestApiToTaxStorageClientInterface $taxStorageClient,
        RestResourceBuilderInterface $restResourceBuilder,
        ProductTaxSetResourceMapperInterface $productTaxSetsResourceMapper
    ) {
        $this->taxProductStorageClient = $taxProductStorageClient;
        $this->taxStorageClient = $taxStorageClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->productTaxSetsResourceMapper = $productTaxSetsResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getTaxSets(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $parentResource = $restRequest->findParentResourceByType(ProductTaxSetsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS);
        if (!$parentResource) {
            return $this->createProductAbstractNotFoundError();
        }

        $restResource = $this->findProductAbstractTaxSetsByProductAbstractSku($parentResource->getId(), $restRequest);
        if (!$restResource) {
            return $this->createProductTaxSetNotFoundError();
        }

        return $restResponse->addResource($restResource);
    }

    /**
     * @param string $productAbstractSku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findProductAbstractTaxSetsByProductAbstractSku(
        string $productAbstractSku,
        RestRequestInterface $restRequest
    ): ?RestResourceInterface {
        $taxProductStorageTransfer = $this->taxProductStorageClient->findTaxProductStorageByProductAbstractSku($productAbstractSku);
        if (!$taxProductStorageTransfer) {
            return null;
        }

        $taxStorageTransfer = $this->taxStorageClient->findTaxSetStorageByIdTaxSet($taxProductStorageTransfer->getIdTaxSet());
        if (!$taxStorageTransfer) {
            return null;
        }

        $restProductTaxSetsAttributesTransfer = $this->productTaxSetsResourceMapper->mapTaxSetStorageTransferToRestProductTaxSetsAttributesTransfer(
            $taxStorageTransfer,
            new RestProductTaxSetsAttributesTransfer()
        );

        return $this->formatRestResource($restProductTaxSetsAttributesTransfer, $taxStorageTransfer->getUuid(), $productAbstractSku);
    }

    /**
     * @param \Generated\Shared\Transfer\RestProductTaxSetsAttributesTransfer $restTaxSetsAttributesTransfer
     * @param string $uuid
     * @param string $parentResourceId
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function formatRestResource(
        RestProductTaxSetsAttributesTransfer $restTaxSetsAttributesTransfer,
        string $uuid,
        string $parentResourceId
    ): RestResourceInterface {
        $restResource = $this->restResourceBuilder->createRestResource(
            ProductTaxSetsRestApiConfig::RESOURCE_PRODUCT_TAX_SETS,
            $uuid,
            $restTaxSetsAttributesTransfer
        );

        $selfLink = sprintf(
            '%s/%s/%s',
            ProductTaxSetsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            $parentResourceId,
            ProductTaxSetsRestApiConfig::RESOURCE_PRODUCT_TAX_SETS
        );

        $restResource->addLink(RestLinkInterface::LINK_SELF, $selfLink);

        return $restResource;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createProductAbstractNotFoundError(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $errorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductTaxSetsRestApiConfig::RESPONSE_CODE_CANT_FIND_ABSTRACT_PRODUCT)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductTaxSetsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_ABSTRACT_PRODUCT);

        return $restResponse->addError($errorTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createProductTaxSetNotFoundError(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductTaxSetsRestApiConfig::RESPONSE_CODE_CANT_FIND_PRODUCT_TAX_SETS)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductTaxSetsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_PRODUCT_TAX_SETS);

        return $restResponse->addError($restErrorTransfer);
    }
}
