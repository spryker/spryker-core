<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CollectorStorageConnector\Communication;

use Spryker\Zed\CollectorStorageConnector\CollectorStorageConnectorDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CollectorStorageConnectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\CollectorStorageConnector\Dependency\Facade\CollectorStorageConnectorToCollectorInterface
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(CollectorStorageConnectorDependencyProvider::FACADE_COLLECTOR);
    }

    /**
     * @return \Spryker\Zed\CollectorStorageConnector\Dependency\Facade\CollectorStorageConnectorToStorageInterface
     */
    public function getStorageFacade()
    {
        return $this->getProvidedDependency(CollectorStorageConnectorDependencyProvider::FACADE_STORAGE);
    }

}
