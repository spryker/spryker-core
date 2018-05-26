<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalf\Business;

use Spryker\Zed\BusinessOnBehalf\Business\Model\CustomerExpander;
use Spryker\Zed\BusinessOnBehalf\Business\Model\CustomerExpanderInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\BusinessOnBehalf\Persistence\BusinessOnBehalfRepositoryInterface getRepository()
 * @method \Spryker\Zed\BusinessOnBehalf\BusinessOnBehalfConfig getConfig()
 */
class BusinessOnBehalfBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\BusinessOnBehalf\Business\Model\CustomerExpanderInterface
     */
    public function createCustomerExpander(): CustomerExpanderInterface
    {
        return new CustomerExpander($this->getRepository());
    }
}
