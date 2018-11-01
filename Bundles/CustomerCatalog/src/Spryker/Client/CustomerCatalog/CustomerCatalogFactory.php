<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerCatalog;

use Spryker\Client\CustomerCatalog\Dependency\Client\CustomerCatalogToCustomerClientInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CustomerCatalogFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CustomerCatalog\Dependency\Client\CustomerCatalogToCustomerClientInterface
     */
    public function getCustomerClient(): CustomerCatalogToCustomerClientInterface
    {
        return $this->getProvidedDependency(CustomerCatalogDependencyProvider::CLIENT_CUSTOMER);
    }
}
