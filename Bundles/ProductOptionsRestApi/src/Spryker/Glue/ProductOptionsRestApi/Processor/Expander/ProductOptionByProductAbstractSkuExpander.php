<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\Builder\ProductOptionRestResourceBuilderInterface;

class ProductOptionByProductAbstractSkuExpander implements ProductOptionByProductAbstractSkuExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductOptionsRestApi\Processor\Builder\ProductOptionRestResourceBuilderInterface
     */
    protected $productOptionRestResourceBuilder;

    /**
     * @param \Spryker\Glue\ProductOptionsRestApi\Processor\Builder\ProductOptionRestResourceBuilderInterface $productOptionRestResourceBuilder
     */
    public function __construct(ProductOptionRestResourceBuilderInterface $productOptionRestResourceBuilder)
    {
        $this->productOptionRestResourceBuilder = $productOptionRestResourceBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $restResources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationships(array $restResources, RestRequestInterface $restRequest): array
    {
        $productAbstractSkus = [];
        foreach ($restResources as $restResource) {
            $productAbstractSkus[] = $restResource->getId();
        }

        $productOptionRestResources = $this->productOptionRestResourceBuilder->getProductOptionsByProductAbstractSkus(
            $productAbstractSkus,
            $restRequest->getMetadata()->getLocale(),
            $restRequest->getSort()
        );
        foreach ($restResources as $restResource) {
            if (empty($productOptionRestResources[$restResource->getId()])) {
                continue;
            }

            foreach ($productOptionRestResources[$restResource->getId()] as $productOptionRestResource) {
                $restResource->addRelationship($productOptionRestResource);
            }
        }

        return $restResources;
    }
}
