<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CatalogProductListConnector;

use Spryker\Client\CatalogProductListConnector\Dependency\Client\CatalogProductListConnectorToCustomerClientInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CatalogProductListConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CatalogProductListConnector\Dependency\Client\CatalogProductListConnectorToCustomerClientInterface
     */
    public function getCustomerClient(): CatalogProductListConnectorToCustomerClientInterface
    {
        return $this->getProvidedDependency(CatalogProductListConnectorDependencyProvider::CLIENT_CUSTOMER);
    }
}
