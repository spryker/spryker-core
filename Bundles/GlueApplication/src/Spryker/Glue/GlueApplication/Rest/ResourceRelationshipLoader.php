<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface;

class ResourceRelationshipLoader implements ResourceRelationshipLoaderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface
     */
    protected $resourceRelationship;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface $resourceRelationshipCollection
     */
    public function __construct(ResourceRelationshipCollectionInterface $resourceRelationshipCollection)
    {
        $this->resourceRelationship = $resourceRelationshipCollection;
    }

    /**
     * @param string $resourceName
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface[]
     */
    public function load(string $resourceName): array
    {
        if ($this->resourceRelationship->hasRelationships($resourceName)) {
            return $this->resourceRelationship->getRelationships($resourceName);
        }

        return [];
    }
}
