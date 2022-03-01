<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConvention\Resource;

use Generated\Shared\Transfer\GlueRequestTransfer;

class ResourceRelationshipLoader implements ResourceRelationshipLoaderInterface
{
    /**
     * @var array<\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RelationshipProviderPluginInterface>
     */
    protected $relationshipProviderPlugins;

    /**
     * @param array<\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RelationshipProviderPluginInterface> $relationshipProviderPlugins
     */
    public function __construct(array $relationshipProviderPlugins)
    {
        $this->relationshipProviderPlugins = $relationshipProviderPlugins;
    }

    /**
     * @param string $resourceName
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return array<\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipPluginInterface>
     */
    public function load(string $resourceName, GlueRequestTransfer $glueRequestTransfer): array
    {
        $resourceRelationships = [];
        foreach ($this->relationshipProviderPlugins as $relationshipProviderPlugin) {
            if (!$relationshipProviderPlugin->isApplicable($glueRequestTransfer)) {
                continue;
            }

            $resourceRelationshipCollection = $relationshipProviderPlugin->getResourceRelationshipCollection();
            if ($resourceRelationshipCollection->hasRelationships($resourceName)) {
                $resourceRelationships[] = $resourceRelationshipCollection->getRelationships($resourceName);
            }
        }

        return array_merge(...$resourceRelationships);
    }
}
