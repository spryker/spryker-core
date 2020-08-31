<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfiguration\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesProductConfiguration\Business\Writer\SalesOrderItemConfigurationWriter;
use Spryker\Zed\SalesProductConfiguration\Business\Writer\SalesOrderItemConfigurationWriterInterface;

/**
 * @method \Spryker\Zed\SalesProductConfiguration\Persistence\SalesProductConfigurationEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesProductConfiguration\Persistence\SalesProductConfigurationRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesProductConfiguration\SalesProductConfigurationConfig getConfig()
 */
class SalesProductConfigurationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesProductConfiguration\Business\Writer\SalesOrderItemConfigurationWriterInterface
     */
    public function createSalesOrderItemConfigurationWriter(): SalesOrderItemConfigurationWriterInterface
    {
        return new SalesOrderItemConfigurationWriter(
            $this->getEntityManager()
        );
    }
}
