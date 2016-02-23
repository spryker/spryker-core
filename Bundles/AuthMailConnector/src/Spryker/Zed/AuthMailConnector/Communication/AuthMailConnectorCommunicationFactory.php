<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthMailConnector\Communication;

use Spryker\Zed\AuthMailConnector\AuthMailConnectorDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\AuthMailConnector\AuthMailConnectorConfig getConfig()
 */
class AuthMailConnectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\AuthMailConnector\Dependency\Facade\AuthMailConnectorToMailInterface
     */
    public function getMailFacade()
    {
        return $this->getProvidedDependency(AuthMailConnectorDependencyProvider::FACADE_MAIL);
    }

}
