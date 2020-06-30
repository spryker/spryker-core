<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi\Processor\AbstractProduct\Expander;

use Spryker\Glue\ContentProductAbstractListsRestApi\ContentProductAbstractListsRestApiConfig;
use Spryker\Glue\ContentProductAbstractListsRestApi\Processor\AbstractProduct\Reader\ProductAbstractReaderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ProductAbstractByContentProductAbstractListExpander implements ProductAbstractByContentProductAbstractListExpanderInterface
{
    /**
     * @var \Spryker\Glue\ContentProductAbstractListsRestApi\Processor\AbstractProduct\Reader\ProductAbstractReaderInterface
     */
    protected $productAbstractReader;

    /**
     * @param \Spryker\Glue\ContentProductAbstractListsRestApi\Processor\AbstractProduct\Reader\ProductAbstractReaderInterface $productAbstractReader
     */
    public function __construct(ProductAbstractReaderInterface $productAbstractReader)
    {
        $this->productAbstractReader = $productAbstractReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $contentProductAbstractListKeys = $this->getContentProductAbstractListKeys($resources);

        $productAbstractRestResources = $this->productAbstractReader
            ->getProductAbstractRestResources($contentProductAbstractListKeys, $restRequest->getMetadata()->getLocale());

        foreach ($resources as $restResource) {
            if (
                !$restResource->getId()
                || $restResource->getType() !== ContentProductAbstractListsRestApiConfig::RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS
                || !isset($productAbstractRestResources[$restResource->getId()])
            ) {
                continue;
            }

            foreach ($productAbstractRestResources[$restResource->getId()] as $productAbstractRestResource) {
                $restResource->addRelationship($productAbstractRestResource);
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return string[]
     */
    protected function getContentProductAbstractListKeys(array $resources): array
    {
        $contentProductAbstractListKeys = [];

        foreach ($resources as $restResource) {
            if (!$restResource->getId() || $restResource->getType() !== ContentProductAbstractListsRestApiConfig::RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS) {
                continue;
            }

            $contentProductAbstractListKeys[] = $restResource->getId();
        }

        return $contentProductAbstractListKeys;
    }
}
