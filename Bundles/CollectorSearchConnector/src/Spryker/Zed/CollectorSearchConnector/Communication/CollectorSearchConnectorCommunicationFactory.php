<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CollectorSearchConnector\Communication;

use Spryker\Zed\CollectorSearchConnector\CollectorSearchConnectorDependencyProvider;
use Spryker\Zed\CollectorSearchConnector\Dependency\Facade\CollectorSearchConnectorToStoreInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CollectorSearchConnectorCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CollectorSearchConnector\Dependency\Facade\CollectorSearchConnectorToCollectorInterface
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(CollectorSearchConnectorDependencyProvider::FACADE_COLLECTOR);
    }

    /**
     * @return \Spryker\Zed\CollectorSearchConnector\Dependency\Facade\CollectorSearchConnectorToStoreInterface
     */
    public function getStoreFacade(): CollectorSearchConnectorToStoreInterface
    {
        return $this->getProvidedDependency(CollectorSearchConnectorDependencyProvider::FACADE_STORE);
    }
}
