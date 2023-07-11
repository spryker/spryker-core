<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Processor\Reader;

use Generated\Shared\Transfer\BulkProductReviewSearchRequestTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;
use Generated\Shared\Transfer\ProductReviewTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\Page;
use Spryker\Glue\GlueApplication\Rest\Request\Data\PageInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewClientInterface;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductReviewsRestApi\Processor\RestResponseBuilder\ProductReviewRestResponseBuilderInterface;
use Spryker\Glue\ProductReviewsRestApi\ProductReviewsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ProductReviewReader implements ProductReviewReaderInterface
{
    /**
     * @var string
     */
    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';

    /**
     * @var string
     */
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var string
     */
    protected const KEY_SKU = 'sku';

    /**
     * @uses \Spryker\Client\ProductReview\Plugin\Elasticsearch\ResultFormatter\ProductReviewsResultFormatterPlugin::NAME
     *
     * @var string
     */
    protected const PRODUCT_REVIEWS = 'productReviews';

    /**
     * @uses \Spryker\Client\ProductReview\Plugin\Elasticsearch\ResultFormatter\PaginatedProductReviewsResultFormatterPlugin::NAME
     *
     * @var string
     */
    protected const PAGINATION = 'pagination';

    /**
     * @uses \Spryker\Client\ProductReview\Plugin\Elasticsearch\QueryExpander\FilterByReviewIdQueryExpanderPlugin::REQUEST_PARAM_ID_PRODUCT_REVIEW
     *
     * @var string
     */
    protected const REQUEST_PARAM_ID_PRODUCT_REVIEW = ProductReviewTransfer::ID_PRODUCT_REVIEW;

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
     * @var \Spryker\Glue\ProductReviewsRestApi\ProductReviewsRestApiConfig
     */
    protected $productReviewsRestApiConfig;

    /**
     * @param \Spryker\Glue\ProductReviewsRestApi\Processor\RestResponseBuilder\ProductReviewRestResponseBuilderInterface $productReviewRestResponseBuilder
     * @param \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewClientInterface $productReviewClient
     * @param \Spryker\Glue\ProductReviewsRestApi\ProductReviewsRestApiConfig $productReviewsRestApiConfig
     */
    public function __construct(
        ProductReviewRestResponseBuilderInterface $productReviewRestResponseBuilder,
        ProductReviewsRestApiToProductStorageClientInterface $productStorageClient,
        ProductReviewsRestApiToProductReviewClientInterface $productReviewClient,
        ProductReviewsRestApiConfig $productReviewsRestApiConfig
    ) {
        $this->productReviewRestResponseBuilder = $productReviewRestResponseBuilder;
        $this->productStorageClient = $productStorageClient;
        $this->productReviewClient = $productReviewClient;
        $this->productReviewsRestApiConfig = $productReviewsRestApiConfig;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getProductReviews(RestRequestInterface $restRequest): RestResponseInterface
    {
        $parentResource = $restRequest->findParentResourceByType(ProductReviewsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS);
        if (!$parentResource || !$parentResource->getId()) {
            return $this->productReviewRestResponseBuilder->createProductAbstractSkuMissingErrorResponse();
        }

        $productAbstractData = $this->productStorageClient->findProductAbstractStorageDataByMapping(
            static::PRODUCT_ABSTRACT_MAPPING_TYPE,
            $parentResource->getId(),
            $restRequest->getMetadata()->getLocale(),
        );

        if (!$productAbstractData) {
            return $this->productReviewRestResponseBuilder->createProductAbstractNotFoundErrorResponse();
        }

        if ($restRequest->getResource()->getId()) {
            return $this->getProductReview($productAbstractData, $restRequest->getResource()->getId());
        }

        if (!$restRequest->getPage()) {
            $restRequest->setPage(new Page(0, $this->productReviewsRestApiConfig->getDefaultReviewsPerPage()));
        }
        $productReviews = $this->getProductReviewsInSearch(
            $productAbstractData[static::KEY_ID_PRODUCT_ABSTRACT],
            $this->createRequestParamsWithPaginationParameters($restRequest->getPage()),
        );

        return $this->productReviewRestResponseBuilder->createProductReviewsCollectionRestResponse(
            $productReviews[static::PRODUCT_REVIEWS],
            $productAbstractData[static::KEY_SKU],
            $productReviews[static::PAGINATION]->getNumFound(),
            $restRequest->getPage()->getLimit(),
        );
    }

    /**
     * @param array<int, array<string, mixed>> $productAbstractDataCollection
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>>
     */
    public function getProductReviewsResourceCollection(array $productAbstractDataCollection, FilterTransfer $filterTransfer): array
    {
        /** @var array<\Generated\Shared\Transfer\ProductReviewTransfer> $productReviewTransfers */
        $productReviewTransfers = $this->getBulkProductReviewsInSearch(
            $this->extractProductAbstractIds($productAbstractDataCollection),
            $filterTransfer,
        )[static::PRODUCT_REVIEWS];

        $indexedProductReviewTransfers = [];
        foreach ($productReviewTransfers as $productReviewTransfer) {
            $indexedProductReviewTransfers[$productReviewTransfer->getFkProductAbstract()][] = $productReviewTransfer;
        }

        return $this->productReviewRestResponseBuilder->createRestResourceCollection($indexedProductReviewTransfers, $productAbstractDataCollection);
    }

    /**
     * @param array<string, mixed> $productAbstractData
     * @param string $resourceId
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getProductReview(array $productAbstractData, string $resourceId): RestResponseInterface
    {
        $productReviews = $this->getProductReviewsInSearch(
            $productAbstractData[static::KEY_ID_PRODUCT_ABSTRACT],
            [static::REQUEST_PARAM_ID_PRODUCT_REVIEW => $resourceId],
        );
        $productReviewTransfers = $productReviews[static::PRODUCT_REVIEWS];
        if (!$productReviewTransfers) {
            return $this->productReviewRestResponseBuilder->createProductReviewNotFoundErrorResponse($resourceId);
        }
        $productReviewTransfer = count($productReviewTransfers) > 1
            ? $this->findProductReviewTransferByIdProductReview($productReviewTransfers, $resourceId)
            : $productReviewTransfers[0];

        if (!$productReviewTransfer) {
            return $this->productReviewRestResponseBuilder->createProductReviewNotFoundErrorResponse($resourceId);
        }

        return $this->productReviewRestResponseBuilder->createProductReviewRestResponse(
            $productReviewTransfer,
            $productAbstractData[static::KEY_SKU],
            Response::HTTP_OK,
        );
    }

    /**
     * @param int $idProductAbstract
     * @param array<string, mixed> $requestParams
     *
     * @return array
     */
    protected function getProductReviewsInSearch(int $idProductAbstract, array $requestParams): array
    {
        return $this->productReviewClient->findProductReviewsInSearch(
            (new ProductReviewSearchRequestTransfer())
                ->setRequestParams($requestParams)
                ->setIdProductAbstract($idProductAbstract),
        );
    }

    /**
     * @param array<int> $productAbstractIds
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array
     */
    protected function getBulkProductReviewsInSearch(
        array $productAbstractIds,
        FilterTransfer $filterTransfer
    ): array {
        $productReviews = $this->productReviewClient->getBulkProductReviewsFromSearch(
            (new BulkProductReviewSearchRequestTransfer())
                ->setFilter($filterTransfer)
                ->setProductAbstractIds($productAbstractIds),
        );

        return $productReviews;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\PageInterface $page
     *
     * @return array
     */
    protected function createRequestParamsWithPaginationParameters(PageInterface $page): array
    {
        return [
            RequestConstantsInterface::QUERY_OFFSET => $page->getOffset(),
            RequestConstantsInterface::QUERY_LIMIT => $page->getLimit(),
        ];
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductReviewTransfer> $productReviewTransfers
     * @param string $idProductReview
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer|null
     */
    protected function findProductReviewTransferByIdProductReview(array $productReviewTransfers, string $idProductReview): ?ProductReviewTransfer
    {
        foreach ($productReviewTransfers as $productReviewTransfer) {
            if ((string)$productReviewTransfer->getIdProductReviewOrFail() === $idProductReview) {
                return $productReviewTransfer;
            }
        }

        return null;
    }

    /**
     * @param array<int, array<string, mixed>> $productAbstractDataCollection
     *
     * @return list<int>
     */
    protected function extractProductAbstractIds(array $productAbstractDataCollection): array
    {
        $productAbstractIds = [];
        foreach ($productAbstractDataCollection as $productAbstractData) {
            $productAbstractIds[] = $productAbstractData[static::KEY_ID_PRODUCT_ABSTRACT];
        }

        return $productAbstractIds;
    }
}
