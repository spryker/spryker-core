<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ProductAbstractReviewResourceRelationshipExpander extends AbstractProductReviewResourceRelationshipExpander implements ProductAbstractReviewResourceRelationshipExpanderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addRelationshipsByAbstractSku(array $resources, RestRequestInterface $restRequest): void
    {
        $abstractSkus = $this->getAllSkus($resources);

        $productsAbstract = $this->productStorageClient->findBulkProductAbstractStorageDataByMapping(
            static::PRODUCT_MAPPING_TYPE,
            $abstractSkus,
            $restRequest->getMetadata()->getLocale()
        );

        if (!$productsAbstract) {
            return;
        }

        $productAbstractIds = [];
        foreach ($productsAbstract as $productAbstract) {
            $productAbstractIds[] = $productAbstract[static::KEY_ID_PRODUCT_ABSTRACT];
        }

        $productReviewsDataRestResourcesData = $this->productReviewReader
            ->getProductReviewsDataByProductAbstractIds(
                $this->createRequestParams(),
                $productAbstractIds
            );

        foreach ($resources as $resource) {
            foreach ($productReviewsDataRestResourcesData as $key => $productReviewsRestResources) {
                $this->addProductReviewsRelationship($key, $productsAbstract, $resource, $productReviewsRestResources);
            }
        }
    }

    /**
     * @param int $idProductAbstract
     * @param array $productsAbstract
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $productReviewsRestResources
     *
     * @return void
     */
    protected function addProductReviewsRelationship(
        int $idProductAbstract,
        array $productsAbstract,
        RestResourceInterface $resource,
        array $productReviewsRestResources
    ): void {
        if (!array_key_exists($idProductAbstract, $productsAbstract)) {
            return;
        }

        $productAbstract = $productsAbstract[$idProductAbstract];
        if ($resource->getId() !== $productAbstract[static::KEY_SKU]
            || $productAbstract[static::KEY_ID_PRODUCT_ABSTRACT] !== $idProductAbstract
        ) {
            return;
        }

        $this->addResourceRelationship($productReviewsRestResources, $resource);
    }
}
