<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToPriceProductInterface;
use Spryker\Zed\ProductLabel\ProductLabelDependencyProvider;

/**
 * @method \Spryker\Zed\ProductLabel\ProductLabelConfig getConfig()
 * @method \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainer getQueryContainer()
 */
class ProductLabelCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToPriceProductInterface
     */
    public function getPriceProductFacade(): ProductLabelToPriceProductInterface
    {
        return $this->getProvidedDependency(ProductLabelDependencyProvider::FACADE_PRICE_PRODUCT);
    }
}
