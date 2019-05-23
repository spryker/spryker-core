<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationsCategoryNodesResourceRelationship;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Dependency\RestResource\NavigationsCategoryNodesResourceRelationshipToCategoriesRestApiResourceInterface;
use Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Processor\Expander\CategoryNodeResourceExpander;
use Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Processor\Expander\CategoryNodeResourceExpanderInterface;

class NavigationsCategoryNodesResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Processor\Expander\CategoryNodeResourceExpanderInterface
     */
    public function createCategoryNodeResourceExpander(): CategoryNodeResourceExpanderInterface
    {
        return new CategoryNodeResourceExpander($this->getCategoriesRestApiResource());
    }

    /**
     * @return \Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Dependency\RestResource\NavigationsCategoryNodesResourceRelationshipToCategoriesRestApiResourceInterface
     */
    public function getCategoriesRestApiResource(): NavigationsCategoryNodesResourceRelationshipToCategoriesRestApiResourceInterface
    {
        return $this->getProvidedDependency(NavigationsCategoryNodesResourceRelationshipDependencyProvider::RESOURCE_CATEGORIES_REST_API);
    }
}
