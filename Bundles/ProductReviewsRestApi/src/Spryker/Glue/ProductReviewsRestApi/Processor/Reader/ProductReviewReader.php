<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Processor\Reader;

use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestProductReviewsAttributesTransfer;
use Spryker\Client\ProductReview\Plugin\Elasticsearch\ResultFormatter\ProductReviewsResultFormatterPlugin;
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

        $productReviewTransfers = $this->findProductReviewsInSearch($restRequest, $parentResource->getId());
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
     * @param string $abstractSku
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function findProductReviewsByAbstractSku(
        RestRequestInterface $restRequest,
        string $abstractSku,
        string $localeName
    ): array {
        $abstractProductData = $this->productStorageClient->findProductAbstractStorageDataByMapping(
            static::PRODUCT_ABSTRACT_MAPPING_TYPE,
            $abstractSku,
            $localeName
        );

        if (!$abstractProductData) {
            return [];
        }

        $productReviewTransfers = $this->findProductReviewsInSearch($restRequest, $abstractSku);

        return $this->prepareRestResourceCollection($abstractSku, $productReviewTransfers);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer[]
     */
    protected function findProductReviewsInSearch(
        RestRequestInterface $restRequest,
        string $idProductAbstract
    ): array {
        return $this->productReviewClient->findProductReviewsInSearch(
            (new ProductReviewSearchRequestTransfer())
                ->setRequestParams($restRequest->getHttpRequest()->query->all())
                ->setIdProductAbstract($idProductAbstract)
        )[ProductReviewsResultFormatterPlugin::NAME];
    }

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\ProductReviewTransfer[] $productReviewTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    protected function prepareRestResourceCollection(string $abstractSku, array $productReviewTransfers): array
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
                $this->createSelfLink($abstractSku, $productReviewTransfer->getIdProductReview())
            );
        }

        return $productReviewResources;
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
     * @param string $abstractSku
     * @param string $idProductReview
     *
     * @return string
     */
    protected function createSelfLink(string $abstractSku, string $idProductReview): string
    {
        return sprintf(
            static::FORMAT_SELF_LINK_PRODUCT_REVIEWS_RESOURCE,
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            $abstractSku,
            ProductReviewsRestApiConfig::RESOURCE_PRODUCT_REVIEWS,
            $idProductReview
        );
    }
}
