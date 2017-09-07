<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnector\Communication;

use Spryker\Zed\CustomerUserConnector\CustomerUserConnectorDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CustomerUserConnectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\CustomerUserConnectorGui\Dependency\QueryContainer\CustomerUserConnectorGuiToUserQueryContainerInterface
     */
    public function getUserQueryContainer()
    {
        return $this->getProvidedDependency(CustomerUserConnectorDependencyProvider::QUERY_CONTAINER_USER);
    }

}
