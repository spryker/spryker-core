<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductLabelGui\Business\Model\PositionUpdater;
use Spryker\Zed\ProductLabelGui\ProductLabelGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductLabelGui\ProductLabelGuiConfig getConfig()
 * @method \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface getQueryContainer()
 */
class ProductLabelGuiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductLabelGui\Business\Model\PositionUpdaterInterface
     */
    public function createPositionUpdater()
    {
        return new PositionUpdater($this->getProductLabelFacade());
    }

    /**
     * @return \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToProductLabelInterface
     */
    protected function getProductLabelFacade()
    {
        return $this->getProvidedDependency(ProductLabelGuiDependencyProvider::FACADE_PRODUCT_LABEL);
    }
}
