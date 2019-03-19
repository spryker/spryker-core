<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationsCategoryNodesResourceRelationship;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Dependency\RestResource\NavigationsCategoryNodesResourceRelationshipToCategoriesRestApiResourceInterface;
use Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Processor\Expander\CategoryNodesResourceExpander;
use Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Processor\Expander\CategoryNodesResourceExpanderInterface;

class NavigationsCategoryNodesResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Processor\Expander\CategoryNodesResourceExpanderInterface
     */
    public function createCategoryNodesResourceExpander(): CategoryNodesResourceExpanderInterface
    {
        return new CategoryNodesResourceExpander($this->getCategoriesResource());
    }

    /**
     * @return \Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Dependency\RestResource\NavigationsCategoryNodesResourceRelationshipToCategoriesRestApiResourceInterface
     */
    public function getCategoriesResource(): NavigationsCategoryNodesResourceRelationshipToCategoriesRestApiResourceInterface
    {
        return $this->getProvidedDependency(NavigationsCategoryNodesResourceRelationshipDependencyProvider::RESOURCE_CATEGORIES);
    }
}
