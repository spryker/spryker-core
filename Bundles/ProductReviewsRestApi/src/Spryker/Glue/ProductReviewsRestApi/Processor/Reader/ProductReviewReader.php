<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Processor\Reader;

use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;
use Generated\Shared\Transfer\RestProductReviewsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewClientInterface;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductReviewsRestApi\Processor\Mapper\ProductReviewMapperInterface;
use Spryker\Glue\ProductReviewsRestApi\ProductReviewsRestApiConfig;

class ProductReviewReader implements ProductReviewReaderInterface
{
    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

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

        return $this->prepareRestResourceCollection($productReviews['productReviews']);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer[] $productReviews
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    protected function prepareRestResourceCollection(array $productReviews): array
    {
        $productReviewResources = [];

        foreach ($productReviews as $productReview) {
            $restProductReviewAttributesTransfer = $this->productReviewMapper
                ->mapProductReviewTransferToRestProductReviewsAttributesTransfer(
                    $productReview,
                    new RestProductReviewsAttributesTransfer()
                );

            $productReviewResources[] = $this->restResourceBuilder->createRestResource(
                ProductReviewsRestApiConfig::RESOURCE_PRODUCT_REVIEWS,
                (string)$productReview->getIdProductReview(),
                $restProductReviewAttributesTransfer
            );
        }

        return $productReviewResources;
    }
}
