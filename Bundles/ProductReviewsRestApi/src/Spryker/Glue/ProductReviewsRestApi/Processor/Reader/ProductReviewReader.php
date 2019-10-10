<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Processor\Reader;

use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestProductReviewsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewClientInterface;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductReviewsRestApi\Processor\Mapper\ProductReviewMapperInterface;
use Spryker\Glue\ProductReviewsRestApi\ProductReviewsRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ProductReviewReader implements ProductReviewReaderInterface
{
    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';

    protected const FORMAT_SELF_LINK_PRODUCT_REVIEWS_RESOURCE = '%s/%s/%s/%s';

    /**
     * @var \Spryker\Glue\ProductReviewsRestApi\Processor\Mapper\ProductReviewMapperInterface
     */
    protected $productReviewMapper;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewClientInterface
     */
    protected $productReviewClient;

    /**
     * @param \Spryker\Glue\ProductReviewsRestApi\Processor\Mapper\ProductReviewMapperInterface $productReviewMapper
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewClientInterface $productReviewClient
     */
    public function __construct(
        ProductReviewMapperInterface $productReviewMapper,
        RestResourceBuilderInterface $restResourceBuilder,
        ProductReviewsRestApiToProductStorageClientInterface $productStorageClient,
        ProductReviewsRestApiToProductReviewClientInterface $productReviewClient
    ) {
        $this->productReviewMapper = $productReviewMapper;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->productStorageClient = $productStorageClient;
        $this->productReviewClient = $productReviewClient;
    }
    
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function findProductReviews(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $parentResource = $restRequest->findParentResourceByType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS);
        if (!$parentResource || !$parentResource->getId()) {
            return $this->createProductAbstractSkuMissingError();
        }

        /** @var \Generated\Shared\Transfer\ProductReviewTransfer[] $productReviewTransfers */
        $productReviewTransfers = $this->productReviewClient->findProductReviewsInSearch(
            (new ProductReviewSearchRequestTransfer())->setIdProductAbstract($parentResource->getId())
        )['productReviews'];

        if (!count($productReviewTransfers)) {
            return $this->addProductReviewNotFoundErrorToResponse($restResponse);
        }

        foreach ($productReviewTransfers as $productReviewTransfer) {
            $restProductReviewAttributesTransfer = $this->productReviewMapper
                ->mapProductReviewTransferToRestProductReviewsAttributesTransfer(
                    $productReviewTransfer,
                    new RestProductReviewsAttributesTransfer()
                );

            $restResource = $this->restResourceBuilder->createRestResource(
                ProductReviewsRestApiConfig::RESOURCE_PRODUCT_REVIEWS,
                $productReviewTransfer->getIdProductReview(),
                $restProductReviewAttributesTransfer
            )->addLink(
                RestLinkInterface::LINK_SELF,
                $this->createSelfLink($parentResource->getId(), $productReviewTransfer->getIdProductReview())
            );

            $restResponse->addResource($restResource);
        }

        return $restResponse;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string $productReviewId
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function findProductReviewById(RestRequestInterface $restRequest, string $productReviewId): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $parentResource = $restRequest->findParentResourceByType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS);
        if (!$parentResource || !$parentResource->getId()) {
            return $this->createProductAbstractSkuMissingError();
        }

        /** @var \Generated\Shared\Transfer\ProductReviewTransfer[] $productReviewTransfers */
        $productReviewTransfers = $this->productReviewClient->findProductReviewsInSearch(
            (new ProductReviewSearchRequestTransfer())->setIdProductAbstract($parentResource->getId())
        )['productReviews'];

        if (!count($productReviewTransfers)) {
            return $this->addProductReviewNotFoundErrorToResponse($restResponse);
        }

        foreach ($productReviewTransfers as $productReviewTransfer) {
            if ($productReviewTransfer->getIdProductReview() === (int)$productReviewId) {
                $restProductReviewAttributesTransfer = $this->productReviewMapper
                    ->mapProductReviewTransferToRestProductReviewsAttributesTransfer(
                        $productReviewTransfer,
                        new RestProductReviewsAttributesTransfer()
                    );

                $restResource = $this->restResourceBuilder->createRestResource(
                    ProductReviewsRestApiConfig::RESOURCE_PRODUCT_REVIEWS,
                    $productReviewTransfer->getIdProductReview(),
                    $restProductReviewAttributesTransfer
                )->addLink(
                    RestLinkInterface::LINK_SELF,
                    $this->createSelfLink($parentResource->getId(), $productReviewTransfer->getIdProductReview())
                );

                $restResponse->addResource($restResource);
            }
        }

        return $restResponse;
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

        $productReviews = $this->productReviewClient->findProductReviewsInSearch(
            (new ProductReviewSearchRequestTransfer())->setIdProductAbstract($sku)
        );

        return $this->prepareRestResourceCollection($sku, $productReviews['productReviews']);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\ProductReviewTransfer[] $productReviewTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    protected function prepareRestResourceCollection(string $sku, array $productReviewTransfers): array
    {
        $productReviewResources = [];

        foreach ($productReviewTransfers as $productReviewTransfer) {
            $restProductReviewAttributesTransfer = $this->productReviewMapper
                ->mapProductReviewTransferToRestProductReviewsAttributesTransfer(
                    $productReviewTransfer,
                    new RestProductReviewsAttributesTransfer()
                );

            $productReviewResources[] = $this->restResourceBuilder->createRestResource(
                ProductReviewsRestApiConfig::RESOURCE_PRODUCT_REVIEWS,
                (string)$productReviewTransfer->getIdProductReview(),
                $restProductReviewAttributesTransfer
            )->addLink(
                RestLinkInterface::LINK_SELF,
                $this->createSelfLink($sku, $productReviewTransfer->getIdProductReview())
            );
        }

        return $productReviewResources;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addProductReviewNotFoundErrorToResponse(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductReviewsRestApiConfig::RESPONSE_CODE_CANT_FIND_PRODUCT_REVIEW)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductReviewsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_PRODUCT_REVIEW);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addProductReviewMissingErrorToResponse(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductReviewsRestApiConfig::RESPONSE_CODE_PRODUCT_REVIEW_ID_IS_MISSING)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ProductReviewsRestApiConfig::RESPONSE_DETAIL_PRODUCT_REVIEW_ID_IS_MISSING);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createProductAbstractSkuMissingError(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @param string $idProductAbstract
     * @param string $idProductReview
     *
     * @return string
     */
    protected function createSelfLink(string $idProductAbstract, string $idProductReview): string
    {
        return sprintf(
            static::FORMAT_SELF_LINK_PRODUCT_REVIEWS_RESOURCE,
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            $idProductAbstract,
            ProductReviewsRestApiConfig::RESOURCE_PRODUCT_REVIEWS,
            $idProductReview
        );
    }
}
