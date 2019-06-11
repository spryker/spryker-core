<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCompanyUser;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\OauthCompanyUser\Dependency\Client\OauthCompanyUserToZedRequestClientInterface;
use Spryker\Client\OauthCompanyUser\Reader\CompanyUserAccessTokenReader;
use Spryker\Client\OauthCompanyUser\Reader\CompanyUserAccessTokenReaderInterface;
use Spryker\Client\OauthCompanyUser\Zed\OauthCompanyUserStub;
use Spryker\Client\OauthCompanyUser\Zed\OauthCompanyUserStubInterface;

/**
 * @method \Spryker\Client\OauthCompanyUser\OauthCompanyUserConfig getConfig()
 */
class OauthCompanyUserFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\OauthCompanyUser\OauthCompanyUserConfig
     */
    public function getModuleConfig()
    {
        /** @var \Spryker\Client\OauthCompanyUser\OauthCompanyUserConfig $config */
        $config = parent::getConfig();

        return $config;
    }

    /**
     * @return \Spryker\Client\OauthCompanyUser\Reader\CompanyUserAccessTokenReaderInterface
     */
    public function createCompanyUserAccessTokenReader(): CompanyUserAccessTokenReaderInterface
    {
        return new CompanyUserAccessTokenReader(
            $this->createOauthCompanyUserStub()
        );
    }

    /**
     * @return \Spryker\Client\OauthCompanyUser\Zed\OauthCompanyUserStubInterface
     */
    public function createOauthCompanyUserStub(): OauthCompanyUserStubInterface
    {
        return new OauthCompanyUserStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\OauthCompanyUser\Dependency\Client\OauthCompanyUserToZedRequestClientInterface
     */
    public function getZedRequestClient(): OauthCompanyUserToZedRequestClientInterface
    {
        return $this->getProvidedDependency(OauthCompanyUserDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
