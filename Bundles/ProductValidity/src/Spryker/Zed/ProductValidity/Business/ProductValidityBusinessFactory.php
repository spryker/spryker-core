<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductValidity\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductValidity\Business\ProductConcrete\ProductConcreteSwitcher;
use Spryker\Zed\ProductValidity\Business\Validity\ProductValidityHydrator;
use Spryker\Zed\ProductValidity\Business\Validity\ProductValidityUpdater;
use Spryker\Zed\ProductValidity\ProductValidityDependencyProvider;

/**
 * @method \Spryker\Zed\ProductValidity\Persistence\ProductValidityQueryContainerInterface getQueryContainer()
 */
class ProductValidityBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductValidity\Business\ProductConcrete\ProductConcreteSwitcherInterface
     */
    public function createProductConcreteSwitcher()
    {
        return new ProductConcreteSwitcher(
            $this->getQueryContainer(),
            $this->getProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductValidity\Business\Validity\ProductValidityHydratorInterface
     */
    public function createProductValidityHydrator()
    {
        return new ProductValidityHydrator(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ProductValidity\Business\Validity\ProductValidityUpdaterInterface
     */
    public function createProductValidityUpdater()
    {
        return new ProductValidityUpdater(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ProductValidity\Dependency\Facade\ProductValidityToProductFacadeInterface
     */
    public function getProductFacade()
    {
        return $this->getProvidedDependency(ProductValidityDependencyProvider::FACADE_PRODUCT);
    }
}
