<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToProductPackagingUnitInterface;
use Spryker\Zed\ProductPackagingUnitGui\ProductPackagingUnitGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductPackagingUnitGui\ProductPackagingUnitGuiConfig getConfig()
 * @method \Spryker\Zed\ProductPackagingUnitGui\Persistence\ProductPackagingUnitGuiRepositoryInterface getRepository()
 */
class ProductPackagingUnitGuiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToProductPackagingUnitInterface
     */
    public function getProductPackagingUnitFacade(): ProductPackagingUnitGuiToProductPackagingUnitInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitGuiDependencyProvider::FACADE_PRODUCT_PACKAGING_UNIT);
    }
}
