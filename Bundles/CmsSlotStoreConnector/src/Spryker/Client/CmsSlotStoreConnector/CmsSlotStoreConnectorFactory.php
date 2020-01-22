<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotStoreConnector;

use Spryker\Client\CmsSlotStoreConnector\Dependency\Client\CmsSlotStoreConnectorToStoreClientInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CmsSlotStoreConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CmsSlotStoreConnector\Dependency\Client\CmsSlotStoreConnectorToStoreClientInterface
     */
    public function getStoreClient(): CmsSlotStoreConnectorToStoreClientInterface
    {
        return $this->getProvidedDependency(CmsSlotStoreConnectorDependencyProvider::CLIENT_STORE);
    }
}
