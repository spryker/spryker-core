<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySalesConnector\Business;

use Spryker\Zed\CompanySalesConnector\Business\Writer\OrderWriter;
use Spryker\Zed\CompanySalesConnector\Business\Writer\OrderWriterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanySalesConnector\Persistence\CompanySalesConnectorEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CompanySalesConnector\Persistence\CompanySalesConnectorRepositoryInterface getRepository()
 */
class CompanySalesConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanySalesConnector\Business\Writer\OrderWriterInterface
     */
    public function createOrderWriter(): OrderWriterInterface
    {
        return new OrderWriter(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }
}
