<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductListGui\ProductListGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductListGui\ProductListGuiConfig getConfig()
 */
class ProductListGuiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductListGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductListGuiDependencyProvider::FACADE_LOCALE);
    }
}
