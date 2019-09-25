<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Plugin\GlueApplication;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\NavigationsCategoryNodesResourceRelationship\NavigationsCategoryNodesResourceRelationshipConfig;

/**
 * @method \Spryker\Glue\NavigationsCategoryNodesResourceRelationship\NavigationsCategoryNodesResourceRelationshipFactory getFactory()
 */
class CategoryNodeByResourceIdResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Adds category node resource as a relationship.
     *  - Collects list of category ids from nodes of navigation resource recursively.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $this->getFactory()
            ->createCategoryNodeResourceExpander()
            ->addResourceRelationshipsByResourceId($resources, $restRequest);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getRelationshipResourceType(): string
    {
        return NavigationsCategoryNodesResourceRelationshipConfig::RESOURCE_CATEGORY_NODES;
    }
}
