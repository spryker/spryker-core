<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConvention\Response;

use ArrayObject;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueJsonApiConvention\Resource\ResourceRelationshipLoaderInterface;

class RelationshipResponseBuilder implements RelationshipResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueJsonApiConvention\Resource\ResourceRelationshipLoaderInterface
     */
    protected $resourceRelationshipProviderLoader;

    /**
     * @var array<string, bool>
     */
    protected $alreadyLoadedResources = [];

    /**
     * @param \Spryker\Glue\GlueJsonApiConvention\Resource\ResourceRelationshipLoaderInterface $resourceRelationshipProviderLoader
     */
    public function __construct(ResourceRelationshipLoaderInterface $resourceRelationshipProviderLoader)
    {
        $this->resourceRelationshipProviderLoader = $resourceRelationshipProviderLoader;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function buildResponse(GlueResponseTransfer $glueResponseTransfer, GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        if (!isset($glueResponseTransfer->getResources()[0])) {
            return $glueResponseTransfer;
        }

        $mainResourceType = $glueResponseTransfer->getResources()[0]->getTypeOrFail();

        $this->loadRelationships(
            $mainResourceType,
            $glueResponseTransfer->getResources()->getArrayCopy(),
            $glueRequestTransfer,
        );

        $glueResponseTransfer->setIncludedRelationships(
            new ArrayObject($this->processIncluded($glueResponseTransfer->getResources()->getArrayCopy(), $glueRequestTransfer)),
        );

        return $glueResponseTransfer;
    }

    /**
     * @param string $resourceName
     * @param array<\Generated\Shared\Transfer\GlueResourceTransfer> $resources
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param string|null $parentResourceId
     *
     * @return void
     */
    public function loadRelationships(
        string $resourceName,
        array $resources,
        GlueRequestTransfer $glueRequestTransfer,
        ?string $parentResourceId = null
    ): void {
        if (!$this->canLoadResource($resourceName, $parentResourceId)) {
            return;
        }

        $resources = $this->applyRelationshipPlugins($resourceName, $resources, $glueRequestTransfer);

        $this->alreadyLoadedResources[$resourceName . $parentResourceId] = true;

        foreach ($resources as $resource) {
            foreach ($resource->getRelationships() as $resourceRelationship) {
                if (!$this->hasRelationship($resourceRelationship->getResources()->getArrayCopy()[0]->getTypeOrFail(), $glueRequestTransfer)) {
                    continue;
                }

                $this->loadRelationships(
                    $resourceRelationship->getResources()->getArrayCopy()[0]->getTypeOrFail(),
                    $resourceRelationship->getResources()->getArrayCopy(),
                    $glueRequestTransfer,
                    $resource->getId(),
                );
            }
        }
    }

    /**
     * @param string $resourceType
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return bool
     */
    protected function hasRelationship(string $resourceType, GlueRequestTransfer $glueRequestTransfer): bool
    {
        if ($resourceType === $glueRequestTransfer->getResourceOrFail()->getType()) {
            return true;
        }

        return in_array($resourceType, $glueRequestTransfer->getIncludedRelationships());
    }

    /**
     * @param string $resourceName
     * @param array<\Generated\Shared\Transfer\GlueResourceTransfer> $resources
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\GlueResourceTransfer>
     */
    protected function applyRelationshipPlugins(string $resourceName, array $resources, GlueRequestTransfer $glueRequestTransfer): array
    {
        $relationshipPlugins = $this->resourceRelationshipProviderLoader->load($resourceName, $glueRequestTransfer);
        foreach ($relationshipPlugins as $relationshipPlugin) {
            if (!$this->hasRelationship($relationshipPlugin->getRelationshipResourceType(), $glueRequestTransfer)) {
                continue;
            }

            $relationshipPlugin->addRelationships($resources, $glueRequestTransfer);
        }

        return $resources;
    }

    /**
     * @param array<\Generated\Shared\Transfer\GlueResourceTransfer> $resources
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return array<mixed>
     */
    public function processIncluded(array $resources, GlueRequestTransfer $glueRequestTransfer): array
    {
        $includedResourceRelationships = [];

        foreach ($resources as $resource) {
            $this->processRelationships($resource->getRelationships()->getArrayCopy(), $glueRequestTransfer, $includedResourceRelationships);
        }

        return array_values($includedResourceRelationships);
    }

    /**
     * @param array<\Generated\Shared\Transfer\GlueRelationshipTransfer> $resourceRelationships
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param array<\Generated\Shared\Transfer\GlueResourceTransfer> $includedResourceRelationships
     *
     * @return void
     */
    protected function processRelationships(
        array $resourceRelationships,
        GlueRequestTransfer $glueRequestTransfer,
        array &$includedResourceRelationships
    ): void {
        foreach ($resourceRelationships as $resources) {
            foreach ($resources->getResources() as $resource) {
                $resourceType = $resource->getTypeOrFail();

                if (!$this->hasRelationship($resourceType, $glueRequestTransfer)) {
                    continue;
                }

                if ($resource->getRelationships()->count() !== 0) {
                    $this->processRelationships((array)$resource->getRelationships(), $glueRequestTransfer, $includedResourceRelationships);
                }

                $resourceId = $resourceType . ':' . $resource->getId();
                if ($this->isResourceCanBeIncluded($includedResourceRelationships, $resourceId)) {
                    $includedResourceRelationships[$resourceId] = $resource;
                }
            }
        }
    }

    /**
     * @param array<\Generated\Shared\Transfer\GlueResourceTransfer> $includedResourceRelationships
     * @param string $resourceId
     *
     * @return bool
     */
    protected function isResourceCanBeIncluded(array $includedResourceRelationships, string $resourceId): bool
    {
        if (!isset($includedResourceRelationships[$resourceId])) {
            return true;
        }

        $resource = $includedResourceRelationships[$resourceId];

        return $resource->getRelationships()->count() === 0;
    }

    /**
     * @param string $resourceType
     * @param string|null $parentResourceId
     *
     * @return bool
     */
    protected function canLoadResource(string $resourceType, ?string $parentResourceId = null): bool
    {
        return !isset($this->alreadyLoadedResources[$resourceType . $parentResourceId]);
    }
}
