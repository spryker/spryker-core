<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductDiscontinuedGui\Dependency\Facade\ProductDiscontinuedGuiToProductDiscontinuedFacadeInterface;
use Spryker\Zed\ProductDiscontinuedGui\ProductDiscontinuedGuiDependencyProvider;

class ProductDiscontinuedGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductDiscontinuedGui\Dependency\Facade\ProductDiscontinuedGuiToProductDiscontinuedFacadeInterface
     */
    public function getProductDiscontinuedFacade(): ProductDiscontinuedGuiToProductDiscontinuedFacadeInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedGuiDependencyProvider::FACADE_PRODUCT_DISCONTINUED);
    }
}
