<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationCategoryNodesResourceRelationship\Dependency\RestResource;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class NavigationCategoryNodesResourceRelationshipToCategoriesRestApiResourceBridge implements NavigationCategoryNodesResourceRelationshipToCategoriesRestApiResourceInterface
{
    /**
     * @var \Spryker\Glue\CategoriesRestApi\CategoriesRestApiResourceInterface
     */
    protected $categoriesResource;

    /**
     * @param \Spryker\Glue\CategoriesRestApi\CategoriesRestApiResourceInterface $categoriesResource
     */
    public function __construct($categoriesResource)
    {
        $this->categoriesResource = $categoriesResource;
    }

    /**
     * @param int $nodeId
     * @param string $locale
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findCategoryNodeById(int $nodeId, string $locale): ?RestResourceInterface
    {
        return $this->categoriesResource->findCategoryNodeById($nodeId, $locale);
    }
}
