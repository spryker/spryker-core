<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CollectorSearchConnector\Communication;

use Spryker\Zed\CollectorSearchConnector\CollectorSearchConnectorDependencyProvider;
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

}
