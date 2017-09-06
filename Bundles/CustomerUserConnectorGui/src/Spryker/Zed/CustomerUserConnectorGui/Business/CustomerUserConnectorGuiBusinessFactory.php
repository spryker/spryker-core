<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnectorGui\Business;

use Spryker\Zed\CustomerUserConnectorGui\Business\Model\CustomerUserConnectionUpdater;
use Spryker\Zed\CustomerUserConnectorGui\CustomerUserConnectorGuiDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class CustomerUserConnectorGuiBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\CustomerUserConnectorGui\Dependency\QueryContainer\CustomerUserConnectorGuiToCustomerQueryContainerInterface
     */
    public function getCustomerQueryContainer()
    {
        return $this->getProvidedDependency(CustomerUserConnectorGuiDependencyProvider::QUERY_CONTAINER_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\CustomerUserConnectorGui\Business\Model\CustomerUserConnectionUpdaterInterface
     */
    public function createCustomerUserConnectionUpdater()
    {
        return new CustomerUserConnectionUpdater($this->getCustomerQueryContainer());
    }

}
