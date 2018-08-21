<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsRestApi\Processor\ConcreteProductsImageSets;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductImageSetsRestApi\Dependency\Client\ProductImageSetsRestApiToProductImageResourceAliasStorageClientInterface;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\ConcreteProductImageSetsMapperInterface;
use Spryker\Glue\ProductImageSetsRestApi\ProductImageSetsRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ConcreteProductImageSetsReader implements ConcreteProductImageSetsReaderInterface
{
    /**
     * @var \Spryker\Glue\ProductImageSetsRestApi\Dependency\Client\ProductImageSetsRestApiToProductImageResourceAliasStorageClientInterface
     */
    protected $productImageStorageClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $resourceBuilder;

    /**
     * @var \Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\ConcreteProductImageSetsMapperInterface
     */
    protected $productImagesMapper;

    /**
     * @param \Spryker\Glue\ProductImageSetsRestApi\Dependency\Client\ProductImageSetsRestApiToProductImageResourceAliasStorageClientInterface $productImageStorageClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $responseBuilder
     * @param \Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\ConcreteProductImageSetsMapperInterface $productImagesMapper
     */
    public function __construct(
        ProductImageSetsRestApiToProductImageResourceAliasStorageClientInterface $productImageStorageClient,
        RestResourceBuilderInterface $responseBuilder,
        ConcreteProductImageSetsMapperInterface $productImagesMapper
    ) {
        $this->productImageStorageClient = $productImageStorageClient;
        $this->resourceBuilder = $responseBuilder;
        $this->productImagesMapper = $productImagesMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function findConcreteProductImageSets(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->resourceBuilder->createRestResponse();

        $parentResource = $restRequest->findParentResourceByType(ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS);
        if (!$parentResource) {
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setCode(ProductsRestApiConfig::RESPONSE_CODE_CANT_FIND_CONCRETE_PRODUCT)
                ->setStatus(Response::HTTP_NOT_FOUND)
                ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_CONCRETE_PRODUCT);

            return $restResponse->addError($restErrorTransfer);
        }

        $parentResourceId = $parentResource->getId();
        $locale = $restRequest->getMetadata()->getLocale();
        $restResource = $this->findOne($parentResourceId, $locale);

        if ($restResource === null) {
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setCode(ProductImageSetsRestApiConfig::RESPONSE_CODE_CONCRETE_PRODUCT_IMAGE_SETS_NOT_FOUND)
                ->setStatus(Response::HTTP_NOT_FOUND)
                ->setDetail(ProductImageSetsRestApiConfig::RESPONSE_DETAIL_CONCRETE_PRODUCT_IMAGE_SETS_NOT_FOUND);

            return $restResponse->addError($restErrorTransfer);
        }

        return $restResponse->addResource($restResource);
    }

    /**
     * @param string $concreteProductId
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findConcreteProductImageSetsByConcreteProductId(string $concreteProductId, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        $locale = $restRequest->getMetadata()->getLocale();

        return $this->findOne($concreteProductId, $locale);
    }

    /**
     * @param string $idResource
     * @param string $locale
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    protected function findOne(string $idResource, string $locale): ?RestResourceInterface
    {
        $productImageConcreteStorageTransfer = $this->productImageStorageClient
            ->findProductImageConcreteStorageTransfer($idResource, $locale);

        if ($productImageConcreteStorageTransfer === null) {
            return null;
        }

        $restResource = $this->productImagesMapper
            ->mapConcreteProductImageSetsTransferToRestResource($productImageConcreteStorageTransfer, $idResource);
        $restResourceSelfLink = sprintf(
            '%s/%s/%s',
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $idResource,
            ProductImageSetsRestApiConfig::RESOURCE_CONCRETE_PRODUCT_IMAGE_SETS
        );
        $restResource->addLink('self', $restResourceSelfLink);

        return $restResource;
    }
}
