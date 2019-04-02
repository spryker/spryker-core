<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCompanyUser;

interface OauthCompanyUserClientInterface
{
    /**
     * Specification:
     *  - Returns client secret used to authenticate Oauth client requests.
     *
     * @api
     *
     * @return string
     */
    public function getClientSecret(): string;

    /**
     * Specification:
     *  - Returns client id used to authenticate Oauth client requests.
     *
     * @api
     *
     * @return string
     */
    public function getClientId(): string;
}
