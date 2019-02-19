<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthCompanyUser;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\OauthCompanyUser\Dependency\Client\OauthCompanyUserToCompanyUserStorageClientInterface;
use Spryker\Glue\OauthCompanyUser\Processor\RestUser\RestUserExpander;
use Spryker\Glue\OauthCompanyUser\Processor\RestUser\RestUserExpanderInterface;

class OauthCompanyUserFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\OauthCompanyUser\Processor\RestUser\RestUserExpanderInterface
     */
    public function createRestUserExpander(): RestUserExpanderInterface
    {
        return new RestUserExpander($this->getCompanyUserStorageClient());
    }

    /**
     * @return \Spryker\Glue\OauthCompanyUser\Dependency\Client\OauthCompanyUserToCompanyUserStorageClientInterface
     */
    public function getCompanyUserStorageClient(): OauthCompanyUserToCompanyUserStorageClientInterface
    {
        return $this->getProvidedDependency(OauthCompanyUserDependencyProvider::CLIENT_COMPANY_USER_STORAGE);
    }
}
