<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business;

use Spryker\Zed\ConfigurableBundle\Business\Expander\SalesOrderConfiguredBundleExpander;
use Spryker\Zed\ConfigurableBundle\Business\Expander\SalesOrderConfiguredBundleExpanderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Writer\SalesOrderConfiguredBundleWriter;
use Spryker\Zed\ConfigurableBundle\Business\Writer\SalesOrderConfiguredBundleWriterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface getRepository()
 * @method \Spryker\Zed\ConfigurableBundle\ConfigurableBundleConfig getConfig()
 */
class ConfigurableBundleBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Writer\SalesOrderConfiguredBundleWriterInterface
     */
    public function createSalesOrderConfiguredBundleWriter(): SalesOrderConfiguredBundleWriterInterface
    {
        return new SalesOrderConfiguredBundleWriter(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Expander\SalesOrderConfiguredBundleExpanderInterface
     */
    public function createSalesOrderConfiguredBundleExpander(): SalesOrderConfiguredBundleExpanderInterface
    {
        return new SalesOrderConfiguredBundleExpander(
            $this->getRepository()
        );
    }
}
