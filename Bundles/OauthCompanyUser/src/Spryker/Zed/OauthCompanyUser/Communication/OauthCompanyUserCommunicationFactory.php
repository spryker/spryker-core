<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser\Communication;

use League\OAuth2\Server\Grant\AbstractGrant;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\OauthCompanyUser\Business\League\Grant\IdCompanyUserGrantType;

/**
 * @method \Spryker\Zed\OauthCompanyUser\Business\OauthCompanyUserFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthCompanyUser\OauthCompanyUserConfig getConfig()
 */
class OauthCompanyUserCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \League\OAuth2\Server\Grant\AbstractGrant
     */
    public function createIdCompanyUserGrantType(): AbstractGrant
    {
        return new IdCompanyUserGrantType();
    }
}
