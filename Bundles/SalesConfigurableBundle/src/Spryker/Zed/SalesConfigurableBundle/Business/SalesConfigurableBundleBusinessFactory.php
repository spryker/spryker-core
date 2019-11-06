<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesConfigurableBundle\Business\Expander\SalesOrderConfiguredBundleExpander;
use Spryker\Zed\SalesConfigurableBundle\Business\Expander\SalesOrderConfiguredBundleExpanderInterface;
use Spryker\Zed\SalesConfigurableBundle\Business\Transformer\ConfigurableBundleItemTransformer;
use Spryker\Zed\SalesConfigurableBundle\Business\Transformer\ConfigurableBundleItemTransformerInterface;
use Spryker\Zed\SalesConfigurableBundle\Business\Writer\SalesOrderConfiguredBundleWriter;
use Spryker\Zed\SalesConfigurableBundle\Business\Writer\SalesOrderConfiguredBundleWriterInterface;
use Spryker\Zed\SalesConfigurableBundle\Dependency\Facade\SalesConfigurableBundleToSalesFacadeInterface;
use Spryker\Zed\SalesConfigurableBundle\SalesConfigurableBundleDependencyProvider;

/**
 * @method \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesConfigurableBundle\SalesConfigurableBundleConfig getConfig()
 */
class SalesConfigurableBundleBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesConfigurableBundle\Business\Writer\SalesOrderConfiguredBundleWriterInterface
     */
    public function createSalesOrderConfiguredBundleWriter(): SalesOrderConfiguredBundleWriterInterface
    {
        return new SalesOrderConfiguredBundleWriter(
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\SalesConfigurableBundle\Business\Expander\SalesOrderConfiguredBundleExpanderInterface
     */
    public function createSalesOrderConfiguredBundleExpander(): SalesOrderConfiguredBundleExpanderInterface
    {
        return new SalesOrderConfiguredBundleExpander(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\SalesConfigurableBundle\Business\Transformer\ConfigurableBundleItemTransformerInterface
     */
    public function createConfigurableBundleItemTransformer(): ConfigurableBundleItemTransformerInterface
    {
        return new ConfigurableBundleItemTransformer(
            $this->getSalesFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\SalesConfigurableBundle\Dependency\Facade\SalesConfigurableBundleToSalesFacadeInterface
     */
    public function getSalesFacade(): SalesConfigurableBundleToSalesFacadeInterface
    {
        return $this->getProvidedDependency(SalesConfigurableBundleDependencyProvider::FACADE_SALES);
    }
}
