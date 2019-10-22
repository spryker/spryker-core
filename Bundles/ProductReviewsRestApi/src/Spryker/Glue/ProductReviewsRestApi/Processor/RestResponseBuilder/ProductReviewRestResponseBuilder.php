<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Processor\RestResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\ProductReviewTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestProductReviewsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\ProductReviewsRestApi\Processor\Mapper\ProductReviewMapperInterface;
use Spryker\Glue\ProductReviewsRestApi\ProductReviewsRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ProductReviewRestResponseBuilder implements ProductReviewRestResponseBuilderInterface
{
    protected const FORMAT_SELF_LINK_PRODUCT_REVIEWS_RESOURCE = '%s/%s';

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductReviewsRestApi\Processor\Mapper\ProductReviewMapperInterface
     */
    protected $productReviewMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductReviewsRestApi\Processor\Mapper\ProductReviewMapperInterface $productReviewMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ProductReviewMapperInterface $productReviewMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->productReviewMapper = $productReviewMapper;
    }

    /**
     * @param int $itemsPerPage
     * @param int $pageLimit
     * @param \Generated\Shared\Transfer\ProductReviewTransfer[] $productReviewTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildProductReviewRestResponse(
        int $itemsPerPage,
        int $pageLimit,
        array $productReviewTransfers
    ): RestResponseInterface {
        $restResponse = $this->createRestResponse(
            $itemsPerPage,
            $pageLimit
        );

        foreach ($productReviewTransfers as $productReviewTransfer) {
            $restResource = $this->createProductReviewRestResource($productReviewTransfer);

            $restResponse->addResource($restResource);
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createProductReviewRestResource(ProductReviewTransfer $productReviewTransfer): RestResourceInterface
    {
        $restProductReviewsAttributesTransfer = $this->productReviewMapper
            ->mapProductReviewTransferToRestProductReviewsAttributesTransfer(
                $productReviewTransfer,
                new RestProductReviewsAttributesTransfer()
            );

        $resourceId = (string)$productReviewTransfer->getIdProductReview();

        return $this->restResourceBuilder->createRestResource(
            ProductReviewsRestApiConfig::RESOURCE_PRODUCT_REVIEWS,
            (string)$productReviewTransfer->getIdProductReview(),
            $restProductReviewsAttributesTransfer
        )->addLink(
            RestLinkInterface::LINK_SELF,
            $this->createSelfLink($resourceId)
        );
    }

    /**
     * @param int $totalItems
     * @param int $limit
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createRestResponse(int $totalItems = 0, int $limit = 0): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse($totalItems, $limit);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductAbstractSkuMissingErrorResponse(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductAbstractNotFoundErrorResponse(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_CANT_FIND_ABSTRACT_PRODUCT)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_ABSTRACT_PRODUCT);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductReviewErrorTransfer[] $productReviewErrorTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductReviewsRestResponseWithErrors(ArrayObject $productReviewErrorTransfers): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        foreach ($productReviewErrorTransfers as $productReviewErrorTransfer) {
            $restResponse->addError((new RestErrorMessageTransfer())
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->setDetail($productReviewErrorTransfer->getMessage()));
        }

        return $restResponse;
    }

    /**
     * @param string $resourceId
     *
     * @return string
     */
    protected function createSelfLink(string $resourceId): string
    {
        return sprintf(
            static::FORMAT_SELF_LINK_PRODUCT_REVIEWS_RESOURCE,
            ProductReviewsRestApiConfig::RESOURCE_PRODUCT_REVIEWS,
            $resourceId
        );
    }
}
