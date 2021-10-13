<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesPayment\Business\Expander\SalesOrderExpander;
use Spryker\Zed\SalesPayment\Business\Expander\SalesOrderExpanderInterface;
use Spryker\Zed\SalesPayment\Business\Writer\SalesPaymentWriter;
use Spryker\Zed\SalesPayment\Business\Writer\SalesPaymentWriterInterface;
use Spryker\Zed\SalesPayment\SalesPaymentDependencyProvider;

/**
 * @method \Spryker\Zed\SalesPayment\SalesPaymentConfig getConfig()
 * @method \Spryker\Zed\SalesPayment\Persistence\SalesPaymentEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesPayment\Persistence\SalesPaymentRepositoryInterface getRepository()
 */
class SalesPaymentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesPayment\Business\Expander\SalesOrderExpanderInterface
     */
    public function createSalesOrderExpander(): SalesOrderExpanderInterface
    {
        return new SalesOrderExpander(
            $this->getRepository(),
            $this->getOrderPaymentExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\SalesPayment\Business\Writer\SalesPaymentWriterInterface
     */
    public function createSalesPaymentWriter(): SalesPaymentWriterInterface
    {
        return new SalesPaymentWriter($this->getEntityManager());
    }

    /**
     * @return array<\Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\OrderPaymentExpanderPluginInterface>
     */
    public function getOrderPaymentExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SalesPaymentDependencyProvider::SALES_PAYMENT_EXPANDER_PLUGINS);
    }
}
