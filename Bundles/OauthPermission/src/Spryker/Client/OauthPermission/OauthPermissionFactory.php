<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthPermission;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToOauthServiceInterface;
use Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToUtilEncodingServiceInterface;
use Spryker\Client\OauthPermission\OauthPermission\OauthPermissionReader;
use Spryker\Client\OauthPermission\OauthPermission\OauthPermissionReaderInterface;

class OauthPermissionFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\OauthPermission\OauthPermission\OauthPermissionReaderInterface
     */
    public function createOauthPermissionReader(): OauthPermissionReaderInterface
    {
        return new OauthPermissionReader(
            $this->getOauthService(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return Dependency\Service\OauthPermissionToOauthServiceInterface
     */
    public function getOauthService(): OauthPermissionToOauthServiceInterface
    {
        return $this->getProvidedDependency(OauthPermissionDependencyProvider::SERVICE_OAUTH);
    }

    /**
     * @return \Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): OauthPermissionToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(OauthPermissionDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
