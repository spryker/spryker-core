<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CustomerDataChangeRequest;

use Spryker\Yves\CustomerDataChangeRequest\Dependency\Client\CustomerDataChangeRequestToCustomerClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\CustomerDataChangeRequest\CustomerDataChangeRequestClientInterface getClient()
 */
class CustomerDataChangeRequestFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\CustomerDataChangeRequest\Dependency\Client\CustomerDataChangeRequestToCustomerClientInterface
     */
    public function getCustomerClient(): CustomerDataChangeRequestToCustomerClientInterface
    {
        return $this->getProvidedDependency(CustomerDataChangeRequestDependencyProvider::CLIENT_CUSTOMER);
    }
}
