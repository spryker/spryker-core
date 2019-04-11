<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthPermission;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToOauthServiceInterface;
use Spryker\Glue\Kernel\Application;
use Spryker\Shared\OauthPermission\OauthPermissionConverter;
use Spryker\Shared\OauthPermission\OauthPermissionConverterInterface;

class OauthPermissionFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\Kernel\Application
     */
    public function getGlueApplication(): Application
    {
        return $this->getProvidedDependency(OauthPermissionDependencyProvider::APPLICATION_GLUE);
    }

    /**
     * @return Dependency\Service\OauthPermissionToOauthServiceInterface
     */
    public function getOauthService(): OauthPermissionToOauthServiceInterface
    {
        return $this->getProvidedDependency(OauthPermissionDependencyProvider::SERVICE_OAUTH);
    }

    /**
     * @return \Spryker\Shared\OauthPermission\OauthPermissionConverterInterface
     */
    public function createOauthPermissionConverter(): OauthPermissionConverterInterface
    {
        return new OauthPermissionConverter();
    }
}
