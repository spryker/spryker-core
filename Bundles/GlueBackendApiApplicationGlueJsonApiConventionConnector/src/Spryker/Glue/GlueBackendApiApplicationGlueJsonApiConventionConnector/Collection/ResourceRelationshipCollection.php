<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplicationGlueJsonApiConventionConnector\Collection;

use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;

class ResourceRelationshipCollection implements ResourceRelationshipCollectionInterface
{
    /**
     * @var array<string, array<\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipPluginInterface>>
     */
    protected array $resourceRelationshipPlugins = [];

    /**
     * @param string $resourceType
     * @param \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipPluginInterface $resourceRelationshipsPlugin
     *
     * @return $this
     */
    public function addRelationship(string $resourceType, ResourceRelationshipPluginInterface $resourceRelationshipsPlugin)
    {
        $this->resourceRelationshipPlugins[$resourceType][] = $resourceRelationshipsPlugin;

        return $this;
    }

    /**
     * @param string $resourceType
     *
     * @return bool
     */
    public function hasRelationships(string $resourceType): bool
    {
        return isset($this->resourceRelationshipPlugins[$resourceType]);
    }

    /**
     * @param string $resourceType
     *
     * @return array<\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipPluginInterface>
     */
    public function getRelationships(string $resourceType): array
    {
        return $this->resourceRelationshipPlugins[$resourceType];
    }
}
