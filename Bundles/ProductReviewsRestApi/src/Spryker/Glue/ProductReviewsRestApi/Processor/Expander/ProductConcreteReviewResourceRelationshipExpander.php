<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ProductConcreteReviewResourceRelationshipExpander extends AbstractProductReviewResourceRelationshipExpander implements ProductConcreteReviewResourceRelationshipExpanderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addRelationshipsByConcreteSku(array $resources, RestRequestInterface $restRequest): void
    {
        $concreteSkus = $this->getAllSkus($resources);

        $productsConcrete = $this->productStorageClient->getProductConcreteStorageDataByMappingAndIdentifiers(
            static::PRODUCT_MAPPING_TYPE,
            $concreteSkus,
            $restRequest->getMetadata()->getLocale()
        );

        if (!$productsConcrete) {
            return;
        }

        $productAbstractIds = [];
        foreach ($productsConcrete as $productConcrete) {
            $productAbstractIds[] = $productConcrete[static::KEY_ID_PRODUCT_ABSTRACT];
        }

        $productReviewsDataRestResourcesData = $this->productReviewReader
            ->getProductReviewsDataByProductAbstractIds(
                $this->createRequestParams(),
                $productAbstractIds
            );

        foreach ($resources as $resource) {
            foreach ($productReviewsDataRestResourcesData as $key => $productReviewsRestResources) {
                $this->addProductReviewsRelationship($key, $productsConcrete, $resource, $productReviewsRestResources);
            }
        }
    }

    /**
     * @param int $idProductAbstract
     * @param array $productsConcrete
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $productReviewsRestResources
     *
     * @return void
     */
    protected function addProductReviewsRelationship(
        int $idProductAbstract,
        array $productsConcrete,
        RestResourceInterface $resource,
        array $productReviewsRestResources
    ): void {
        foreach ($productsConcrete as $productConcrete) {
            if ($idProductAbstract !== $productConcrete[static::KEY_ID_PRODUCT_ABSTRACT]) {
                continue;
            }

            if ($resource->getId() !== $productConcrete[static::KEY_SKU]
                || $productConcrete[static::KEY_ID_PRODUCT_ABSTRACT] !== $idProductAbstract
            ) {
                continue;
            }

            $this->addResourceRelationship($productReviewsRestResources, $resource);
        }
    }
}
