<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi\Processor\Expander;

use Spryker\Glue\ContentProductAbstractListsRestApi\ContentProductAbstractListsRestApiConfig;
use Spryker\Glue\ContentProductAbstractListsRestApi\Processor\Reader\ContentProductAbstractListProductReaderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ProductAbstractByContentProductAbstractListExpander implements ProductAbstractByContentProductAbstractListExpanderInterface
{
    /**
     * @var \Spryker\Glue\ContentProductAbstractListsRestApi\Processor\Reader\ContentProductAbstractListProductReaderInterface
     */
    protected $contentProductAbstractReader;

    /**
     * @param \Spryker\Glue\ContentProductAbstractListsRestApi\Processor\Reader\ContentProductAbstractListProductReaderInterface $contentProductAbstractReader
     */
    public function __construct(ContentProductAbstractListProductReaderInterface $contentProductAbstractReader)
    {
        $this->contentProductAbstractReader = $contentProductAbstractReader;
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

        $productAbstractRestResources = $this->contentProductAbstractReader
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
