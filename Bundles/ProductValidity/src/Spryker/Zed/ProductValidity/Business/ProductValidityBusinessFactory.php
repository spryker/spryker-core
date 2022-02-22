<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductValidity\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductValidity\Business\ProductConcrete\ProductConcreteSwitcher;
use Spryker\Zed\ProductValidity\Business\Validity\ProductValidityReader;
use Spryker\Zed\ProductValidity\Business\Validity\ProductValidityReaderInterface;
use Spryker\Zed\ProductValidity\Business\Validity\ProductValidityUpdater;
use Spryker\Zed\ProductValidity\ProductValidityDependencyProvider;

/**
 * @method \Spryker\Zed\ProductValidity\Persistence\ProductValidityRepositoryInterface getRepository()()
 * @method \Spryker\Zed\ProductValidity\Persistence\ProductValidityQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductValidity\ProductValidityConfig getConfig()
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
            $this->getProductFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductValidity\Business\Validity\ProductValidityReaderInterface
     */
    public function createProductValidityReader(): ProductValidityReaderInterface
    {
        return new ProductValidityReader(
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductValidity\Business\Validity\ProductValidityUpdaterInterface
     */
    public function createProductValidityUpdater()
    {
        return new ProductValidityUpdater(
            $this->getQueryContainer(),
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
