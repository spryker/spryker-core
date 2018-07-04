<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Business;

use Spryker\Zed\CustomerGroup\Business\CustomerGroup\CustomerGroupFinder;
use Spryker\Zed\CustomerGroup\Business\CustomerGroup\CustomerGroupFinderInterface;
use Spryker\Zed\CustomerGroup\Business\Model\CustomerGroup;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CustomerGroup\CustomerGroupConfig getConfig()
 * @method \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface getQueryContainer()
 */
class CustomerGroupBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CustomerGroup\Business\Model\CustomerGroupInterface
     */
    public function createCustomerGroup()
    {
        return new CustomerGroup($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\CustomerGroup\Business\CustomerGroup\CustomerGroupFinderInterface
     */
    public function createCustomerGroupFinder(): CustomerGroupFinderInterface
    {
        return new CustomerGroupFinder(
            $this->getQueryContainer()
        );
    }
}
