<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart;

use Spryker\Client\Kernel\AbstractFactory;

class SharedCartFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\SharedCart\Dependency\Client\SharedCartToCustomerClientInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(SharedCartDependencyProvider::CLIENT_CUSTOMER);
    }
}
