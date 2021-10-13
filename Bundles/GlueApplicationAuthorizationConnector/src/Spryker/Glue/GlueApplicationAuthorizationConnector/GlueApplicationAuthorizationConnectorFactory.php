<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationAuthorizationConnector;

use Spryker\Glue\GlueApplicationAuthorizationConnector\Dependency\Client\GlueApplicationAuthorizationConnectorToAuthorizationClientInterface;
use Spryker\Glue\GlueApplicationAuthorizationConnector\Processor\AuthorizationChecker\AuthorizationChecker;
use Spryker\Glue\GlueApplicationAuthorizationConnector\Processor\AuthorizationChecker\AuthorizationCheckerInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class GlueApplicationAuthorizationConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\GlueApplicationAuthorizationConnector\Processor\AuthorizationChecker\AuthorizationCheckerInterface
     */
    public function createAuthorizationChecker(): AuthorizationCheckerInterface
    {
        return new AuthorizationChecker($this->getAuthorizationClient());
    }

    /**
     * @return \Spryker\Glue\GlueApplicationAuthorizationConnector\Dependency\Client\GlueApplicationAuthorizationConnectorToAuthorizationClientInterface
     */
    public function getAuthorizationClient(): GlueApplicationAuthorizationConnectorToAuthorizationClientInterface
    {
        return $this->getProvidedDependency(GlueApplicationAuthorizationConnectorDependencyProvider::CLIENT_AUTHORIZATION);
    }
}
