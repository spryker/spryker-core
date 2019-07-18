<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\TaxProductConnector;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\TaxProductConnector\Dependency\Client\TaxProductConnectorToZedRequestClientInterface;
use Spryker\Client\TaxProductConnector\Zed\TaxProductConnectorStub;
use Spryker\Client\TaxProductConnector\Zed\TaxProductConnectorStubInterface;

class TaxProductConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\TaxProductConnector\Zed\TaxProductConnectorStubInterface
     */
    public function createZedStub(): TaxProductConnectorStubInterface
    {
        return new TaxProductConnectorStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\TaxProductConnector\Dependency\Client\TaxProductConnectorToZedRequestClientInterface
     */
    protected function getZedRequestClient(): TaxProductConnectorToZedRequestClientInterface
    {
        return $this->getProvidedDependency(TaxProductConnectorDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
