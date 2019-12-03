<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Processor\Creator;

use Generated\Shared\Transfer\ProductReviewRequestTransfer;
use Generated\Shared\Transfer\RestProductReviewsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewClientInterface;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductReviewsRestApi\Processor\RestResponseBuilder\ProductReviewRestResponseBuilderInterface;
use Spryker\Glue\ProductReviewsRestApi\ProductReviewsRestApiConfig;

class ProductReviewCreator implements ProductReviewCreatorInterface
{
    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';

    /**
     * @uses \Spryker\Client\ProductStorage\Mapper\ProductStorageToProductConcreteTransferDataMapper::ID_PRODUCT_ABSTRACT
     */
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var \Spryker\Glue\ProductReviewsRestApi\Processor\RestResponseBuilder\ProductReviewRestResponseBuilderInterface
     */
    protected $productReviewRestResponseBuilder;

    /**
     * @var \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewClientInterface
     */
    protected $productReviewClient;

    /**
     * @var \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \Spryker\Glue\ProductReviewsRestApi\Processor\RestResponseBuilder\ProductReviewRestResponseBuilderInterface $productReviewRestResponseBuilder
     * @param \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewClientInterface $productReviewClient
     * @param \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface $productStorageClient
     */
    public function __construct(
        ProductReviewRestResponseBuilderInterface $productReviewRestResponseBuilder,
        ProductReviewsRestApiToProductReviewClientInterface $productReviewClient,
        ProductReviewsRestApiToProductStorageClientInterface $productStorageClient
    ) {
        $this->productReviewRestResponseBuilder = $productReviewRestResponseBuilder;
        $this->productReviewClient = $productReviewClient;
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestProductReviewsAttributesTransfer $restProductReviewsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductReview(
        RestRequestInterface $restRequest,
        RestProductReviewsAttributesTransfer $restProductReviewsAttributesTransfer
    ): RestResponseInterface {
        $parentResource = $restRequest->findParentResourceByType(ProductReviewsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS);
        if (!$parentResource || !$parentResource->getId()) {
            return $this->productReviewRestResponseBuilder->createProductAbstractSkuMissingErrorResponse();
        }

        $productAbstractData = $this->productStorageClient->findProductAbstractStorageDataByMapping(
            static::PRODUCT_ABSTRACT_MAPPING_TYPE,
            $parentResource->getId(),
            $restRequest->getMetadata()->getLocale()
        );

        if (!$productAbstractData) {
            return $this->productReviewRestResponseBuilder->createProductAbstractNotFoundErrorResponse();
        }

        $productReviewResponseTransfer = $this->productReviewClient->submitCustomerReview(
            $this->createProductReviewRequestTransfer(
                $restProductReviewsAttributesTransfer,
                $productAbstractData[static::KEY_ID_PRODUCT_ABSTRACT],
                $restRequest
            )
        );

        if (!$productReviewResponseTransfer->getIsSuccess()) {
            return $this->productReviewRestResponseBuilder
                ->createProductReviewsRestResponseWithErrors($productReviewResponseTransfer->getErrors());
        }

        return $this->productReviewRestResponseBuilder
            ->createProductReviewRestResponse($productReviewResponseTransfer->getProductReview());
    }

    /**
     * @param \Generated\Shared\Transfer\RestProductReviewsAttributesTransfer $restProductReviewsAttributesTransfer
     * @param int $idProductAbstract
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\ProductReviewRequestTransfer
     */
    protected function createProductReviewRequestTransfer(
        RestProductReviewsAttributesTransfer $restProductReviewsAttributesTransfer,
        int $idProductAbstract,
        RestRequestInterface $restRequest
    ): ProductReviewRequestTransfer {
        return (new ProductReviewRequestTransfer())->fromArray($restProductReviewsAttributesTransfer->toArray())
            ->setIdProductAbstract($idProductAbstract)
            ->setLocaleName($restRequest->getMetadata()->getLocale())
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier());
    }
}
