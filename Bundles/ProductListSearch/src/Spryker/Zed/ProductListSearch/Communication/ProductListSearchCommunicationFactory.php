<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductListSearch\Communication\Dependency\Facade\ProductListSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductListSearch\ProductListSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductListSearch\ProductListSearchConfig getConfig()
 */
class ProductListSearchCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductListSearch\Communication\Dependency\Facade\ProductListSearchToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): ProductListSearchToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ProductListSearchDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
