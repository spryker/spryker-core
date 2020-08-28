<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySalesConnector\Business;

use Spryker\Zed\CompanySalesConnector\Business\Checker\FilterFieldChecker;
use Spryker\Zed\CompanySalesConnector\Business\Checker\FilterFieldCheckerInterface;
use Spryker\Zed\CompanySalesConnector\Business\Checker\PermissionChecker;
use Spryker\Zed\CompanySalesConnector\Business\Checker\PermissionCheckerInterface;
use Spryker\Zed\CompanySalesConnector\Business\Expander\OrderSearchQueryExpander;
use Spryker\Zed\CompanySalesConnector\Business\Expander\OrderSearchQueryExpanderInterface;
use Spryker\Zed\CompanySalesConnector\Business\Writer\OrderWriter;
use Spryker\Zed\CompanySalesConnector\Business\Writer\OrderWriterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanySalesConnector\Persistence\CompanySalesConnectorEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CompanySalesConnector\CompanySalesConnectorConfig getConfig()
 */
class CompanySalesConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanySalesConnector\Business\Writer\OrderWriterInterface
     */
    public function createOrderWriter(): OrderWriterInterface
    {
        return new OrderWriter(
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\CompanySalesConnector\Business\Checker\FilterFieldCheckerInterface
     */
    public function createFilterFieldChecker(): FilterFieldCheckerInterface
    {
        return new FilterFieldChecker();
    }

    /**
     * @return \Spryker\Zed\CompanySalesConnector\Business\Expander\OrderSearchQueryExpanderInterface
     */
    public function createOrderSearchQueryExpander(): OrderSearchQueryExpanderInterface
    {
        return new OrderSearchQueryExpander();
    }

    /**
     * @return \Spryker\Zed\CompanySalesConnector\Business\Checker\PermissionCheckerInterface
     */
    public function createPermissionChecker(): PermissionCheckerInterface
    {
        return new PermissionChecker();
    }
}
