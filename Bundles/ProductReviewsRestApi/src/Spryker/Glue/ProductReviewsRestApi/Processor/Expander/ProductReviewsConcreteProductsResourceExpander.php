<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Processor\Expander;

use Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewStorageClientInterface;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface;

class ProductReviewsConcreteProductsResourceExpander implements ProductReviewsConcreteProductsResourceExpanderInterface
{
    /**
     * @uses \Spryker\Client\ProductStorage\Mapper\ProductStorageToProductConcreteTransferDataMapper::ID_PRODUCT_ABSTRACT
     */
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewStorageClientInterface
     */
    protected $productReviewStorageClient;

    /**
     * @var \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewStorageClientInterface $productReviewStorageClient
     * @param \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface $productStorageClient
     */
    public function __construct(
        ProductReviewsRestApiToProductReviewStorageClientInterface $productReviewStorageClient,
        ProductReviewsRestApiToProductStorageClientInterface $productStorageClient
    ) {
        $this->productReviewStorageClient = $productReviewStorageClient;
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer $concreteProductsRestAttributesTransfer
     * @param int $idProductConcrete
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer
     */
    public function expand(
        ConcreteProductsRestAttributesTransfer $concreteProductsRestAttributesTransfer,
        int $idProductConcrete,
        RestRequestInterface $restRequest
    ): ConcreteProductsRestAttributesTransfer {
        $productConcreteData = $this->productStorageClient->findProductConcreteStorageData(
            $idProductConcrete,
            $restRequest->getMetadata()->getLocale()
        );

        $productReviewStorageTransfer = $this->productReviewStorageClient
            ->findProductAbstractReview($productConcreteData[static::KEY_ID_PRODUCT_ABSTRACT]);
        if (!$productReviewStorageTransfer) {
            $concreteProductsRestAttributesTransfer->setReviewCount(0);

            return $concreteProductsRestAttributesTransfer;
        }

        return $concreteProductsRestAttributesTransfer->fromArray($productReviewStorageTransfer->toArray(), true);
    }
}
