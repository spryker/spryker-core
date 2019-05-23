<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Dependency\RestResource;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class NavigationsCategoryNodesResourceRelationshipToCategoriesRestApiResourceBridge implements NavigationsCategoryNodesResourceRelationshipToCategoriesRestApiResourceInterface
{
    /**
     * @var \Spryker\Glue\CategoriesRestApi\CategoriesRestApiResourceInterface
     */
    protected $categoriesRestApiResource;

    /**
     * @param \Spryker\Glue\CategoriesRestApi\CategoriesRestApiResourceInterface $categoriesRestApiResource
     */
    public function __construct($categoriesRestApiResource)
    {
        $this->categoriesRestApiResource = $categoriesRestApiResource;
    }

    /**
     * @param int $nodeId
     * @param string $locale
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findCategoryNodeById(int $nodeId, string $locale): ?RestResourceInterface
    {
        return $this->categoriesRestApiResource->findCategoryNodeById($nodeId, $locale);
    }
}
