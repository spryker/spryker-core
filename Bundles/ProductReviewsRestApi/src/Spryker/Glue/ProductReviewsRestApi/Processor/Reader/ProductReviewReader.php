<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Processor\Reader;

use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;
use Spryker\Client\ProductReview\Plugin\Elasticsearch\ResultFormatter\PaginatedProductReviewsResultFormatterPlugin;
use Spryker\Client\ProductReview\Plugin\Elasticsearch\ResultFormatter\ProductReviewsResultFormatterPlugin;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\Page;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewClientInterface;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductReviewsRestApi\Processor\RestResponseBuilder\ProductReviewRestResponseBuilderInterface;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;

class ProductReviewReader implements ProductReviewReaderInterface
{
    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';

    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    protected const DEFAULT_REVIEWS_PER_PAGE = 10;

    /**
     * @var \Spryker\Glue\ProductReviewsRestApi\Processor\RestResponseBuilder\ProductReviewRestResponseBuilderInterface
     */
    protected $productReviewRestResponseBuilder;

    /**
     * @var \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewClientInterface
     */
    protected $productReviewClient;

    /**
     * @param \Spryker\Glue\ProductReviewsRestApi\Processor\RestResponseBuilder\ProductReviewRestResponseBuilderInterface $productReviewRestResponseBuilder
     * @param \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewClientInterface $productReviewClient
     */
    public function __construct(
        ProductReviewRestResponseBuilderInterface $productReviewRestResponseBuilder,
        ProductReviewsRestApiToProductStorageClientInterface $productStorageClient,
        ProductReviewsRestApiToProductReviewClientInterface $productReviewClient
    ) {
        $this->productReviewRestResponseBuilder = $productReviewRestResponseBuilder;
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
        if ($restRequest->getResource()->getId()) {
            return $this->productReviewRestResponseBuilder->createNotImplementedErrorResponse();
        }

        $parentResource = $this->findAbstractProductsParentResource($restRequest);
        if (!$parentResource) {
            return $this->productReviewRestResponseBuilder->createProductAbstractSkuMissingErrorResponse();
        }

        $abstractProductData = $this->productStorageClient->findProductAbstractStorageDataByMapping(
            static::PRODUCT_ABSTRACT_MAPPING_TYPE,
            $parentResource->getId(),
            $restRequest->getMetadata()->getLocale()
        );

        if (!$abstractProductData) {
            return $this->productReviewRestResponseBuilder->createProductAbstractNotFoundErrorResponse();
        }

        $productReviews = $this->findProductReviewsInSearch($restRequest, $abstractProductData[static::KEY_ID_PRODUCT_ABSTRACT]);

        if (!$restRequest->getPage()) {
            $restRequest->setPage(new Page(0, static::DEFAULT_REVIEWS_PER_PAGE));
        }

        return $this->productReviewRestResponseBuilder->buildProductReviewRestResponse(
            $productReviews[PaginatedProductReviewsResultFormatterPlugin::NAME]->getNumFound(),
            $restRequest->getPage()->getLimit(),
            $productReviews[ProductReviewsResultFormatterPlugin::NAME]
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    protected function findAbstractProductsParentResource(RestRequestInterface $restRequest): ?RestResourceInterface
    {
        $parentResource = $restRequest->findParentResourceByType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS);
        if (!$parentResource || !$parentResource->getId()) {
            return null;
        }

        return $parentResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param int $idProductAbstract
     * @param array $requestParams
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function findProductReviewsByIdProductAbstract(
        RestRequestInterface $restRequest,
        int $idProductAbstract,
        array $requestParams = []
    ): array {
        $productReviewTransfers = $this->findProductReviewsInSearch(
            $restRequest,
            $idProductAbstract,
            $requestParams
        )[ProductReviewsResultFormatterPlugin::NAME];

        return $this->prepareRestResourceCollection($productReviewTransfers);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param int $idProductAbstract
     * @param array $requestParams
     *
     * @return array
     */
    protected function findProductReviewsInSearch(
        RestRequestInterface $restRequest,
        int $idProductAbstract,
        array $requestParams = []
    ): array {
        $restRequestParams = $restRequest->getHttpRequest()->query->all();
        if (isset($restRequestParams[RequestConstantsInterface::QUERY_PAGE][RequestConstantsInterface::QUERY_OFFSET])) {
            $requestParams[RequestConstantsInterface::QUERY_OFFSET]
                = $restRequestParams[RequestConstantsInterface::QUERY_PAGE][RequestConstantsInterface::QUERY_OFFSET];
        }

        $requestParams[RequestConstantsInterface::QUERY_LIMIT] = $this->getLimitParameter($restRequestParams);

        $productReviews = $this->productReviewClient->findProductReviewsInSearch(
            (new ProductReviewSearchRequestTransfer())
                ->setRequestParams($requestParams)
                ->setIdProductAbstract($idProductAbstract)
        );

        return $productReviews;
    }

    /**
     * @param array $restRequestParams
     *
     * @return int
     */
    protected function getLimitParameter(array $restRequestParams): int
    {
        if (!isset($restRequestParams[RequestConstantsInterface::QUERY_PAGE][RequestConstantsInterface::QUERY_LIMIT])) {
            return static::DEFAULT_REVIEWS_PER_PAGE;
        }

        return (int)$restRequestParams[RequestConstantsInterface::QUERY_PAGE][RequestConstantsInterface::QUERY_LIMIT];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer[] $productReviewTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    protected function prepareRestResourceCollection(array $productReviewTransfers): array
    {
        $productReviewResources = [];
        foreach ($productReviewTransfers as $productReviewTransfer) {
            $productReviewResources[] = $this->productReviewRestResponseBuilder
                ->createProductReviewRestResource($productReviewTransfer);
        }

        return $productReviewResources;
    }
}
