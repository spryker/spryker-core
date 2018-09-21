<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessStorage\Business;

use Spryker\Zed\CustomerAccessStorage\Business\CustomerAccessPublisher\CustomerAccessStorage;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CustomerAccessStorage\CustomerAccessStorageConfig getConfig()
 */
class CustomerAccessStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CustomerAccessStorage\Business\CustomerAccessPublisher\CustomerAccessStorageInterface
     */
    public function createCustomerAccessStorage()
    {
        return new CustomerAccessStorage($this->getRepository(), $this->getEntityManager());
    }
}
