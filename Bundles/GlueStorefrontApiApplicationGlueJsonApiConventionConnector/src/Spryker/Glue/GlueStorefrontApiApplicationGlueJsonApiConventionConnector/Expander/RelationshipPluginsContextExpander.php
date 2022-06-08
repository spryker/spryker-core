<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplicationGlueJsonApiConventionConnector\Expander;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\RelationshipPluginsContextTransfer;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;

class RelationshipPluginsContextExpander implements ContextExpanderInterface
{
    /**
     * @var \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface
     */
    protected $resourceRelationshipCollection;

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
        foreach ($apiApplicationSchemaContextTransfer->getResourceContexts() as $resourceContext) {
            $resourceType = $resourceContext->getResourceTypeOrFail();
            if (!in_array($resourceType, $resourceTypes)) {
                if (!$this->resourceRelationshipCollection->hasRelationships($resourceType)) {
                    continue;
                }
                $relationships = '';
                foreach ($this->resourceRelationshipCollection->getRelationships($resourceType) as $resourceRelationship) {
                    $relationshipPluginsContextExpander = $this->mapRelationshipPluginsContextTransfer($resourceRelationship, $resourceType);
                    $apiApplicationSchemaContextTransfer->addRelationshipPluginsContext($relationshipPluginsContextExpander);
                    $relationships .= $resourceRelationship->getRelationshipResourceType() . ',';
                }
                if ($relationships) {
                    $resourceContext->setRelationships(trim($relationships, ','));
                }
                $resourceTypes[] = $resourceContext->getResourceType();
            }
        }

        return $apiApplicationSchemaContextTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipPluginInterface $resourceRelationship
     * @param string $resourceType
     *
     * @return \Generated\Shared\Transfer\RelationshipPluginsContextTransfer
     */
    protected function mapRelationshipPluginsContextTransfer(
        ResourceRelationshipPluginInterface $resourceRelationship,
        string $resourceType
    ): RelationshipPluginsContextTransfer {
        $relationshipPluginsContextExpander = new RelationshipPluginsContextTransfer();
        $relationshipPluginsContextExpander->setResourcePluginName(get_class($resourceRelationship));
        $relationshipPluginsContextExpander->setResourceType($resourceType);
        $relationshipPluginsContextExpander->setRelationship($resourceRelationship->getRelationshipResourceType());

        return $relationshipPluginsContextExpander;
    }
}
