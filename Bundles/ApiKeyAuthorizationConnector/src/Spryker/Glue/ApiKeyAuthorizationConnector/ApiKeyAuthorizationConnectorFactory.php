<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ApiKeyAuthorizationConnector;

use Spryker\Glue\ApiKeyAuthorizationConnector\Dependency\Facade\ApiKeyAuthorizationConnectorToApiKeyInterface;
use Spryker\Glue\ApiKeyAuthorizationConnector\Expander\ApiKeyAuthorizationRequestExpander;
use Spryker\Glue\ApiKeyAuthorizationConnector\Expander\ApiKeyAuthorizationRequestExpanderInterface;
use Spryker\Glue\Kernel\Backend\AbstractFactory;

/**
 * @method \Spryker\Glue\ApiKeyAuthorizationConnector\ApiKeyAuthorizationConnectorConfig getConfig()
 */
class ApiKeyAuthorizationConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ApiKeyAuthorizationConnector\Expander\ApiKeyAuthorizationRequestExpanderInterface
     */
    public function createApiKeyAuthorizationRequestExpander(): ApiKeyAuthorizationRequestExpanderInterface
    {
        return new ApiKeyAuthorizationRequestExpander(
            $this->getApiKeyFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Glue\ApiKeyAuthorizationConnector\Dependency\Facade\ApiKeyAuthorizationConnectorToApiKeyInterface
     */
    public function getApiKeyFacade(): ApiKeyAuthorizationConnectorToApiKeyInterface
    {
        return $this->getProvidedDependency(ApiKeyAuthorizationConnectorDependencyProvider::FACADE_API_KEY);
    }
}
