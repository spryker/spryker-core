<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\OauthRevoke\Business\Creator\OauthRefreshTokenCreator;
use Spryker\Zed\OauthRevoke\Business\Creator\OauthRefreshTokenCreatorInterface;
use Spryker\Zed\OauthRevoke\Dependency\Service\OauthRevokeToUtilEncodingServiceInterface;
use Spryker\Zed\OauthRevoke\OauthRevokeDependencyProvider;

/**
 * @method \Spryker\Zed\OauthRevoke\OauthRevokeConfig getConfig()
 * @method \Spryker\Zed\OauthRevoke\Persistence\OauthRevokeEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\OauthRevoke\Persistence\OauthRevokeRepositoryInterface getRepository()
 */
class OauthRevokeBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\OauthRevoke\Business\Creator\OauthRefreshTokenCreatorInterface
     */
    public function createOauthRefreshTokenCreator(): OauthRefreshTokenCreatorInterface
    {
        return new OauthRefreshTokenCreator(
            $this->getEntityManager(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\OauthRevoke\Dependency\Service\OauthRevokeToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): OauthRevokeToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(OauthRevokeDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
