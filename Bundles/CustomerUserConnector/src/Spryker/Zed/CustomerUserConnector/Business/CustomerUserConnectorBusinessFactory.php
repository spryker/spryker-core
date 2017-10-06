<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnector\Business;

use Spryker\Zed\CustomerUserConnector\Business\Model\CustomerUserConnectionUpdater;
use Spryker\Zed\CustomerUserConnector\CustomerUserConnectorDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CustomerUserConnector\CustomerUserConnectorConfig getConfig()
 */
class CustomerUserConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\CustomerUserConnector\Business\Model\CustomerUserConnectionUpdaterInterface
     */
    public function createCustomerUserConnectionUpdater()
    {
        return new CustomerUserConnectionUpdater(
            $this->getProvidedDependency(CustomerUserConnectorDependencyProvider::QUERY_CONTAINER_CUSTOMER)
        );
    }

}
