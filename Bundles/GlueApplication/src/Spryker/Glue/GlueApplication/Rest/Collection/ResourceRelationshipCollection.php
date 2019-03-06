<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Collection;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;

class ResourceRelationshipCollection implements ResourceRelationshipCollectionInterface
{
    /**
     * @var array
     */
    protected $relationships = [];

    /**
     * @param string $resourceType
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface $resourceRelationshipsPlugin
     *
     * @return $this
     */
    public function addRelationship(string $resourceType, ResourceRelationshipPluginInterface $resourceRelationshipsPlugin)
    {
        $this->relationships[$resourceType][] = $resourceRelationshipsPlugin;

        return $this;
    }

    /**
     * @param string $resourceType
     *
     * @return bool
     */
    public function hasRelationships(string $resourceType): bool
    {
        return isset($this->relationships[$resourceType]);
    }

    /**
     * @param string $resourceType
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface[]
     */
    public function getRelationships(string $resourceType): array
    {
        return $this->relationships[$resourceType];
    }
}
