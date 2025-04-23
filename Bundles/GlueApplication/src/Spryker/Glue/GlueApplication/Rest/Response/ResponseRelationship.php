<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Response;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\ResourceRelationshipLoaderInterface;

class ResponseRelationship implements ResponseRelationshipInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\ResourceRelationshipLoaderInterface
     */
    protected $resourceRelationshipProviderLoader;

    /**
     * @var array
     */
    protected $alreadyLoadedResources = [];

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\ResourceRelationshipLoaderInterface $resourceRelationshipProviderLoader
     */
    public function __construct(ResourceRelationshipLoaderInterface $resourceRelationshipProviderLoader)
    {
        $this->resourceRelationshipProviderLoader = $resourceRelationshipProviderLoader;
    }

    /**
     * @param string $resourceName
     * @param array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string|null $parentResourceId
     *
     * @return void
     */
    public function loadRelationships(
        string $resourceName,
        array $resources,
        RestRequestInterface $restRequest,
        ?string $parentResourceId = null
    ): void {
        if (!$this->canLoadResource($resourceName, $restRequest, $parentResourceId)) {
            return;
        }

        $resources = $this->applyRelationshipPlugins($resourceName, $resources, $restRequest);

        $this->alreadyLoadedResources[$resourceName . $parentResourceId] = true;

        foreach ($resources as $resource) {
            foreach ($resource->getRelationships() as $resourceType => $resourceRelationships) {
                if (!$this->hasRelationship($resourceType, $restRequest)) {
                    continue;
                }
                $this->loadRelationships($resourceType, $resourceRelationships, $restRequest, $resource->getId());
            }
        }
    }

    /**
     * @param array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array
     */
    public function processIncluded(array $resources, RestRequestInterface $restRequest): array
    {
        $included = [];
        foreach ($resources as $resource) {
            $this->processRelationships($resource->getRelationships(), $restRequest, $included);
        }

        return array_values($included);
    }

    /**
     * @param array $resourceRelationships
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param array $included
     *
     * @return void
     */
    protected function processRelationships(
        array $resourceRelationships,
        RestRequestInterface $restRequest,
        array &$included
    ): void {
        /** @var array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $resources */
        foreach ($resourceRelationships as $resourceType => $resources) {
            if (!$this->hasRelationship($resourceType, $restRequest)) {
                continue;
            }
            foreach ($resources as $resource) {
                if ($resource->getRelationships()) {
                    $this->processRelationships($resource->getRelationships(), $restRequest, $included);
                }

                $resourceId = $resourceType . ':' . $resource->getId();
                if ($this->isResourceCanBeIncluded($included, $resourceId)) {
                    $included[$resourceId] = $resource;
                }
            }
        }
    }

    /**
     * @param array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $included
     * @param string $resourceId
     *
     * @return bool
     */
    protected function isResourceCanBeIncluded(array $included, string $resourceId): bool
    {
        if (!isset($included[$resourceId])) {
            return true;
        }

        $resource = $included[$resourceId];

        return !$resource->getRelationships();
    }

    /**
     * @param string $resourceType
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    public function hasRelationship(string $resourceType, RestRequestInterface $restRequest): bool
    {
        if ($restRequest->getResource()->getType() === $resourceType) {
            return true;
        }

        $includes = $restRequest->getInclude();

        return ($includes && isset($includes[$resourceType])) || (!$includes && !$restRequest->getExcludeRelationship());
    }

    /**
     * @param string $resourceName
     * @param array $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array
     */
    protected function applyRelationshipPlugins(string $resourceName, array $resources, RestRequestInterface $restRequest): array
    {
        $relationshipPlugins = $this->resourceRelationshipProviderLoader->load($resourceName);
        foreach ($relationshipPlugins as $relationshipPlugin) {
            if (!$this->hasRelationship($relationshipPlugin->getRelationshipResourceType(), $restRequest)) {
                continue;
            }

            $relationshipPlugin->addResourceRelationships($resources, $restRequest);
        }

        return $resources;
    }

    /**
     * @param string $resourceType
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string|null $parentResourceId
     *
     * @return bool
     */
    protected function canLoadResource(
        string $resourceType,
        RestRequestInterface $restRequest,
        ?string $parentResourceId = null
    ): bool {
        $resourceIndex = $resourceType . $parentResourceId;

        return !isset($this->alreadyLoadedResources[$resourceIndex]) && $restRequest->getExcludeRelationship() === false;
    }
}
