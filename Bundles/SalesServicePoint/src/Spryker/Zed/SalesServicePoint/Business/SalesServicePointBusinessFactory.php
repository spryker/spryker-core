<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesServicePoint\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesServicePoint\Business\Collector\SalesServicePointSalesOrderItemCollector;
use Spryker\Zed\SalesServicePoint\Business\Collector\SalesServicePointSalesOrderItemCollectorInterface;
use Spryker\Zed\SalesServicePoint\Business\Deleter\SalesOrderItemServicePointDeleter;
use Spryker\Zed\SalesServicePoint\Business\Deleter\SalesOrderItemServicePointDeleterInterface;
use Spryker\Zed\SalesServicePoint\Business\Expander\ServicePointExpander;
use Spryker\Zed\SalesServicePoint\Business\Expander\ServicePointExpanderInterface;
use Spryker\Zed\SalesServicePoint\Business\Saver\SalesOrderItemServicePointsSaver;
use Spryker\Zed\SalesServicePoint\Business\Saver\SalesOrderItemServicePointsSaverInterface;

/**
 * @method \Spryker\Zed\SalesServicePoint\Persistence\SalesServicePointEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesServicePoint\Persistence\SalesServicePointRepositoryInterface getRepository()
 */
class SalesServicePointBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesServicePoint\Business\Expander\ServicePointExpanderInterface
     */
    public function createServicePointExpander(): ServicePointExpanderInterface
    {
        return new ServicePointExpander($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\SalesServicePoint\Business\Saver\SalesOrderItemServicePointsSaverInterface
     */
    public function createSalesOrderItemServicePointsSaver(): SalesOrderItemServicePointsSaverInterface
    {
        return new SalesOrderItemServicePointsSaver(
            $this->getEntityManager(),
            $this->createSalesOrderItemServicePointDeleter(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesServicePoint\Business\Deleter\SalesOrderItemServicePointDeleterInterface
     */
    public function createSalesOrderItemServicePointDeleter(): SalesOrderItemServicePointDeleterInterface
    {
        return new SalesOrderItemServicePointDeleter($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\SalesServicePoint\Business\Collector\SalesServicePointSalesOrderItemCollectorInterface
     */
    public function createSalesServicePointSalesOrderItemCollector(): SalesServicePointSalesOrderItemCollectorInterface
    {
        return new SalesServicePointSalesOrderItemCollector();
    }
}
