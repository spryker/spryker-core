<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnector\Communication;

use Spryker\Zed\CustomerUserConnector\CustomerUserConnectorDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CustomerUserConnector\CustomerUserConnectorConfig getConfig()
 * @method \Spryker\Zed\CustomerUserConnector\Business\CustomerUserConnectorFacadeInterface getFacade()
 */
class CustomerUserConnectorCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CustomerUserConnector\Dependency\QueryContainer\CustomerUserConnectorToUserQueryContainerInterface
     */
    public function getUserQueryContainer()
    {
        return $this->getProvidedDependency(CustomerUserConnectorDependencyProvider::QUERY_CONTAINER_USER);
    }
}
