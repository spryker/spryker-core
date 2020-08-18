<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundlesRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductBundlesRestApi\Processor\Reader\BundledProductReaderInterface;
use Spryker\Glue\ProductBundlesRestApi\ProductBundlesRestApiConfig;

class BundledProductExpander implements BundledProductExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductBundlesRestApi\Processor\Reader\BundledProductReaderInterface
     */
    protected $bundledProductReader;

    /**
     * @param \Spryker\Glue\ProductBundlesRestApi\Processor\Reader\BundledProductReaderInterface $bundledProductReader
     */
    public function __construct(BundledProductReaderInterface $bundledProductReader)
    {
        $this->bundledProductReader = $bundledProductReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addBundledProductsRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $productConcreteSkus = $this->getProductConcreteSkus($resources);
        $bundledProductRestResources = $this->bundledProductReader
            ->getBundledProductRestResourcesByProductConcreteSkus($productConcreteSkus, $restRequest);

        foreach ($resources as $resource) {
            if (
                $resource->getType() !== ProductBundlesRestApiConfig::RESOURCE_CONCRETE_PRODUCTS
                || !isset($bundledProductRestResources[$resource->getId()])
            ) {
                continue;
            }

            foreach ($bundledProductRestResources[$resource->getId()] as $bundledProductRestResource) {
                $resource->addRelationship($bundledProductRestResource);
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return string[]
     */
    protected function getProductConcreteSkus(array $resources): array
    {
        $productConcreteSkus = [];
        foreach ($resources as $resource) {
            if ($resource->getType() !== ProductBundlesRestApiConfig::RESOURCE_CONCRETE_PRODUCTS || !$resource->getId()) {
                continue;
            }

            $productConcreteSkus[] = $resource->getId();
        }

        return $productConcreteSkus;
    }
}
