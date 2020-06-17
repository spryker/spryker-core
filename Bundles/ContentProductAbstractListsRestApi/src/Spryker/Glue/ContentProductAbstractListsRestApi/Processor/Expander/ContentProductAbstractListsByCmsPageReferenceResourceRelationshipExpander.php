<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi\Processor\Expander;

use Spryker\Glue\ContentProductAbstractListsRestApi\Processor\Reader\ContentProductAbstractListReaderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ContentProductAbstractListsByCmsPageReferenceResourceRelationshipExpander implements ContentProductAbstractListsByCmsPageReferenceResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ContentProductAbstractListsRestApi\Processor\Reader\ContentProductAbstractListReaderInterface
     */
    protected $contentProductAbstractListReader;

    /**
     * @param \Spryker\Glue\ContentProductAbstractListsRestApi\Processor\Reader\ContentProductAbstractListReaderInterface $contentProductAbstractListReader
     */
    public function __construct(ContentProductAbstractListReaderInterface $contentProductAbstractListReader)
    {
        $this->contentProductAbstractListReader = $contentProductAbstractListReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $cmsPageReferences = $this->getCmsPageReferences($resources);

        $contentProductAbstractListsResources = $this->contentProductAbstractListReader
            ->getContentProductAbstractListsResources($cmsPageReferences, $restRequest);

        foreach ($resources as $resource) {
            $cmsPageReference = $resource->getId();
            if (!isset($contentProductAbstractListsResources[$cmsPageReference])) {
                continue;
            }

            foreach ($contentProductAbstractListsResources[$cmsPageReference] as $contentProductAbstractListsResources) {
                $resource->addRelationship($contentProductAbstractListsResources);
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return string[]
     */
    protected function getCmsPageReferences(array $resources): array
    {
        $references = [];
        foreach ($resources as $resource) {
            $resourceId = $resource->getId();
            if (!$resourceId) {
                continue;
            }

            $references[] = $resourceId;
        }

        return $references;
    }
}
