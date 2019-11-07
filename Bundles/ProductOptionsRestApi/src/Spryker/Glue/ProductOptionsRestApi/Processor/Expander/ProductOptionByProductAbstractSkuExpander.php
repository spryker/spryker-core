<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\Reader\ProductOptionReaderInterface;

class ProductOptionByProductAbstractSkuExpander implements ProductOptionByProductAbstractSkuExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductOptionsRestApi\Processor\Reader\ProductOptionReaderInterface
     */
    protected $productOptionReader;

    /**
     * @param \Spryker\Glue\ProductOptionsRestApi\Processor\Reader\ProductOptionReaderInterface $productOptionReader
     */
    public function __construct(ProductOptionReaderInterface $productOptionReader)
    {
        $this->productOptionReader = $productOptionReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $restResources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationships(array $restResources, RestRequestInterface $restRequest): array
    {
        $productAbstractSkus = array_map(function (RestResourceInterface $restResource) {
            return $restResource->getId();
        }, $restResources);

        $productOptionRestResources = $this->productOptionReader->getByProductAbstractSkus(
            $productAbstractSkus,
            $restRequest->getMetadata()->getLocale()
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
