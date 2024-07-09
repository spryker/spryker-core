<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Customer;

use Spryker\Yves\Customer\Processor\CurrentCustomerDataRequestLogProcessor;
use Spryker\Yves\Customer\Processor\CurrentCustomerDataRequestLogProcessorInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use Symfony\Component\HttpFoundation\RequestStack;

class CustomerFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\Customer\Processor\CurrentCustomerDataRequestLogProcessorInterface
     */
    public function createCurrentCustomerDataRequestLogProcessor(): CurrentCustomerDataRequestLogProcessorInterface
    {
        return new CurrentCustomerDataRequestLogProcessor($this->getRequestStackService());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStackService(): RequestStack
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::SERVICE_REQUEST_STACK);
    }
}
