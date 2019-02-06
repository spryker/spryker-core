<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CompanyUserAuthRestApi;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class CompanyUserAuthRestApiConfig extends AbstractBundleConfig
{
    /**
     * The client secret used to authenticate Oauth client requests, to create use "password_hash('your password', PASSWORD_BCRYPT)".
     *
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->get(CompanyUserAuthRestApiConstants::OAUTH_CLIENT_SECRET);
    }

    /**
     * The identifier of Oauth client.
     *
     * @return string
     */
    public function getClientId(): string
    {
        return $this->get(CompanyUserAuthRestApiConstants::OAUTH_CLIENT_IDENTIFIER);
    }
}
