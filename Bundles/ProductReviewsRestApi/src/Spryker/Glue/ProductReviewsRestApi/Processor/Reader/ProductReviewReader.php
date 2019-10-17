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
use Spryker\Glue\GlueApplication\Rest\Request\Data\Page;
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
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    protected const FORMAT_SELF_LINK_PRODUCT_REVIEWS_RESOURCE = '%s/%s/%s';

    /**
     * @uses \Spryker\Client\Catalog\Plugin\Config\CatalogSearchConfigBuilder::DEFAULT_ITEMS_PER_PAGE;
     */
    protected const DEFAULT_ITEMS_PER_PAGE = 3;

    /**
     * @uses \Spryker\Client\Catalog\Plugin\Config\CatalogSearchConfigBuilder::PARAMETER_NAME_PAGE;
     */
    protected const PARAMETER_NAME_PAGE = 'page';

    /**
     * @uses \Spryker\Client\Catalog\Plugin\Config\CatalogSearchConfigBuilder::PARAMETER_NAME_ITEMS_PER_PAGE;
     */
    protected const PARAMETER_NAME_ITEMS_PER_PAGE = 'ipp';

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
        $parentResource = $restRequest->findParentResourceByType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS);
        if (!$parentResource || !$parentResource->getId()) {
            return $this->createProductAbstractSkuMissingError();
        }

        $abstractProductData = $this->productStorageClient->findProductAbstractStorageDataByMapping(
            static::PRODUCT_ABSTRACT_MAPPING_TYPE,
            $parentResource->getId(),
            $restRequest->getMetadata()->getLocale()
        );

        if (!$abstractProductData) {
            return $this->createProductAbstractNotFoundError();
        }

        $productReviews = $this->findProductReviewsInSearch(
            $restRequest,
            $abstractProductData[static::KEY_ID_PRODUCT_ABSTRACT]
        );

        if (!$restRequest->getPage()) {
            $restRequest->setPage(new Page(1, static::DEFAULT_ITEMS_PER_PAGE));
        }
        $restResponse = $this->restResourceBuilder->createRestResponse(
            $productReviews['pagination']->getNumFound(),
            $restRequest->getPage()->getLimit()
        );

        /** @var \Generated\Shared\Transfer\ProductReviewTransfer[] $productReviewTransfers */
        $productReviewTransfers = $productReviews[ProductReviewsResultFormatterPlugin::NAME];
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
                $this->createSelfLink($parentResource->getId())
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

        $productReviewTransfers = $this->findProductReviewsInSearch(
            $restRequest,
            $abstractProductData[static::KEY_ID_PRODUCT_ABSTRACT]
        )[ProductReviewsResultFormatterPlugin::NAME];

        return $this->prepareRestResourceCollection($abstractSku, $productReviewTransfers);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string $idProductAbstract
     *
     * @return array
     */
    protected function findProductReviewsInSearch(
        RestRequestInterface $restRequest,
        string $idProductAbstract
    ): array {
        return $this->productReviewClient->findProductReviewsInSearch(
            (new ProductReviewSearchRequestTransfer())
                ->setRequestParams($restRequest->getHttpRequest()->query->all())
                ->setIdProductAbstract($idProductAbstract)
        );
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
                $this->createSelfLink($abstractSku)
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
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createProductAbstractNotFoundError(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_CANT_FIND_ABSTRACT_PRODUCT)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_ABSTRACT_PRODUCT);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @param string $abstractSku
     *
     * @return string
     */
    protected function createSelfLink(string $abstractSku): string
    {
        return sprintf(
            static::FORMAT_SELF_LINK_PRODUCT_REVIEWS_RESOURCE,
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            $abstractSku,
            ProductReviewsRestApiConfig::RESOURCE_PRODUCT_REVIEWS
        );
    }
}
