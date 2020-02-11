<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotLocaleConnector;

use Spryker\Client\CmsSlotLocaleConnector\Dependency\Client\CmsSlotLocaleConnectorToLocaleClientInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CmsSlotLocaleConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CmsSlotLocaleConnector\Dependency\Client\CmsSlotLocaleConnectorToLocaleClientInterface
     */
    public function getLocaleClient(): CmsSlotLocaleConnectorToLocaleClientInterface
    {
        return $this->getProvidedDependency(CmsSlotLocaleConnectorDependencyProvider::CLIENT_LOCALE);
    }
}
