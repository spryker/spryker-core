<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesContentBannersResourceRelationship\Processor\Expander;

use Spryker\Glue\CmsPagesContentBannersResourceRelationship\Processor\Reader\ContentBannerReaderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ContentBannerByCmsPageUuidResourceRelationshipExpander implements ContentBannerByCmsPageUuidResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\CmsPagesContentBannersResourceRelationship\Processor\Reader\ContentBannerReaderInterface
     */
    protected $contentBannerReader;

    /**
     * @param \Spryker\Glue\CmsPagesContentBannersResourceRelationship\Processor\Reader\ContentBannerReaderInterface $contentBannerReader
     */
    public function __construct(ContentBannerReaderInterface $contentBannerReader)
    {
        $this->contentBannerReader = $contentBannerReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $cmsPageUuids = $this->getCmsPageUuids($resources);

        $contentBannersResources = $this->contentBannerReader
            ->getContentBannersResources($cmsPageUuids, $restRequest);

        foreach ($resources as $resource) {
            $cmsPageReference = $resource->getId();
            if (!isset($contentBannersResources[$cmsPageReference])) {
                continue;
            }

            foreach ($contentBannersResources[$cmsPageReference] as $contentBannersResource) {
                $resource->addRelationship($contentBannersResource);
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return string[]
     */
    protected function getCmsPageUuids(array $resources): array
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
