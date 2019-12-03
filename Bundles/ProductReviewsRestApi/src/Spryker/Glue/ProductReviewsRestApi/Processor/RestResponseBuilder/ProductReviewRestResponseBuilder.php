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
use Symfony\Component\HttpFoundation\Response;

class ProductReviewRestResponseBuilder implements ProductReviewRestResponseBuilderInterface
{
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
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductReviewRestResponse(ProductReviewTransfer $productReviewTransfer): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $restResponse->addResource($this->createProductReviewRestResource($productReviewTransfer));

        return $restResponse->setStatus(Response::HTTP_ACCEPTED);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer[] $productReviewTransfers
     * @param int $totalItems
     * @param int $pageLimit
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductReviewsCollectionRestResponse(
        array $productReviewTransfers,
        int $totalItems = 0,
        int $pageLimit = 0
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse(
            $totalItems,
            $pageLimit
        );

        foreach ($productReviewTransfers as $productReviewTransfer) {
            $restResource = $this->createProductReviewRestResource($productReviewTransfer);

            $restResponse->addResource($restResource);
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer[][] $indexedProductReviewTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][]
     */
    public function createRestResourceCollection(array $indexedProductReviewTransfers): array
    {
        $productReviewRestResourceCollection = [];
        foreach ($indexedProductReviewTransfers as $idProductAbstract => $productReviewTransfers) {
            foreach ($productReviewTransfers as $productReviewTransfer) {
                $productReviewRestResourceCollection[$idProductAbstract][] = $this->createProductReviewRestResource($productReviewTransfer);
            }
        }

        return $productReviewRestResourceCollection;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductAbstractSkuMissingErrorResponse(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductReviewsRestApiConfig::RESPONSE_CODE_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ProductReviewsRestApiConfig::RESPONSE_DETAIL_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductAbstractNotFoundErrorResponse(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductReviewsRestApiConfig::RESPONSE_CODE_CANT_FIND_ABSTRACT_PRODUCT)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductReviewsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_ABSTRACT_PRODUCT);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createNotImplementedErrorResponse(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_NOT_IMPLEMENTED)
            ->setDetail(ProductReviewsRestApiConfig::RESPONSE_DETAIL_RESOURCE_NOT_IMPLEMENTED);

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
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createProductReviewRestResource(ProductReviewTransfer $productReviewTransfer): RestResourceInterface
    {
        $restProductReviewsAttributesTransfer = $this->productReviewMapper
            ->mapProductReviewTransferToRestProductReviewsAttributesTransfer(
                $productReviewTransfer,
                new RestProductReviewsAttributesTransfer()
            );

        $resourceId = (string)$productReviewTransfer->getIdProductReview();

        return $this->restResourceBuilder->createRestResource(
            ProductReviewsRestApiConfig::RESOURCE_PRODUCT_REVIEWS,
            $resourceId,
            $restProductReviewsAttributesTransfer
        )->addLink(
            RestLinkInterface::LINK_SELF,
            $this->createSelfLink($resourceId)
        );
    }

    /**
     * @param string $resourceId
     *
     * @return string
     */
    protected function createSelfLink(string $resourceId): string
    {
        return sprintf(
            '%s/%s',
            ProductReviewsRestApiConfig::RESOURCE_PRODUCT_REVIEWS,
            $resourceId
        );
    }
}
