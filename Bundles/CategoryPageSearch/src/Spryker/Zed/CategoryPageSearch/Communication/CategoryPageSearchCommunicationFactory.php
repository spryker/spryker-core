<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Communication;

use Spryker\Zed\CategoryPageSearch\CategoryPageSearchDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CategoryPageSearch\CategoryPageSearchConfig getConfig()
 * @method \Spryker\Zed\CategoryPageSearch\Business\CategoryPageSearchFacadeInterface getFacade()
 */
class CategoryPageSearchCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(CategoryPageSearchDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
