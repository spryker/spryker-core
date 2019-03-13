<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationCategoryNodesResourceRelationship;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\NavigationCategoryNodesResourceRelationship\Dependency\RestResource\NavigationCategoryNodesResourceRelationshipToCategoriesRestApiInterface;
use Spryker\Glue\NavigationCategoryNodesResourceRelationship\Processor\Expander\CategoryNodesResourceExpander;
use Spryker\Glue\NavigationCategoryNodesResourceRelationship\Processor\Expander\CategoryNodesResourceExpanderInterface;

class NavigationCategoryNodesResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\NavigationCategoryNodesResourceRelationship\Processor\Expander\CategoryNodesResourceExpanderInterface
     */
    public function createCategoryNodesResourceExpander(): CategoryNodesResourceExpanderInterface
    {
        return new CategoryNodesResourceExpander($this->getCategoriesResource());
    }

    /**
     * @return \Spryker\Glue\NavigationCategoryNodesResourceRelationship\Dependency\RestResource\NavigationCategoryNodesResourceRelationshipToCategoriesRestApiInterface
     */
    public function getCategoriesResource(): NavigationCategoryNodesResourceRelationshipToCategoriesRestApiInterface
    {
        return $this->getProvidedDependency(NavigationCategoryNodesResourceRelationshipDependencyProvider::RESOURCE_CATEGORIES);
    }
}
