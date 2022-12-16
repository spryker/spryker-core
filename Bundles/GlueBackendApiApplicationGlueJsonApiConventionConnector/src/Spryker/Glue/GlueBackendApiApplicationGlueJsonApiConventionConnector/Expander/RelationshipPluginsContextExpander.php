<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplicationGlueJsonApiConventionConnector\Expander;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\RelationshipPluginsContextTransfer;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;

class RelationshipPluginsContextExpander implements ContextExpanderInterface
{
    /**
     * @var \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface
     */
    protected ResourceRelationshipCollectionInterface $resourceRelationshipCollection;

    /**
     * @param \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface $resourceRelationshipCollection
     */
    public function __construct(ResourceRelationshipCollectionInterface $resourceRelationshipCollection)
    {
        $this->resourceRelationshipCollection = $resourceRelationshipCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
     */
    public function expand(ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer): ApiApplicationSchemaContextTransfer
    {
        $resourceTypes = [];
        foreach ($apiApplicationSchemaContextTransfer->getResourceContexts() as $resourceContextTransfer) {
            $resourceType = $resourceContextTransfer->getResourceTypeOrFail();
            if (in_array($resourceType, $resourceTypes, true)) {
                continue;
            }

            if (!$this->resourceRelationshipCollection->hasRelationships($resourceType)) {
                continue;
            }

            $relationships = $this->getResourceRelationships($apiApplicationSchemaContextTransfer, $resourceType);
            if ($relationships !== '') {
                $resourceContextTransfer->setRelationships($relationships);
            }

            $resourceTypes[] = $resourceType;
        }

        return $apiApplicationSchemaContextTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     * @param string $resourceType
     *
     * @return string
     */
    protected function getResourceRelationships(ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer, string $resourceType): string
    {
        $relationships = [];
        foreach ($this->resourceRelationshipCollection->getRelationships($resourceType) as $resourceRelationshipPlugin) {
            $relationshipPluginsContextTransfer = $this->createRelationshipPluginsContextTransfer($resourceRelationshipPlugin, $resourceType);
            $apiApplicationSchemaContextTransfer->addRelationshipPluginsContext($relationshipPluginsContextTransfer);
            $relationships[] = $resourceRelationshipPlugin->getRelationshipResourceType();
        }

        return implode(',', $relationships);
    }

    /**
     * @param \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipPluginInterface $resourceRelationshipPlugin
     * @param string $resourceType
     *
     * @return \Generated\Shared\Transfer\RelationshipPluginsContextTransfer
     */
    protected function createRelationshipPluginsContextTransfer(
        ResourceRelationshipPluginInterface $resourceRelationshipPlugin,
        string $resourceType
    ): RelationshipPluginsContextTransfer {
        return (new RelationshipPluginsContextTransfer())
            ->setResourcePluginName(get_class($resourceRelationshipPlugin))
            ->setResourceType($resourceType)
            ->setRelationship($resourceRelationshipPlugin->getRelationshipResourceType());
    }
}
