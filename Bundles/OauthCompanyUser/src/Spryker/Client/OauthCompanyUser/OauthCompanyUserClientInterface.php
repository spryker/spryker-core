<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCompanyUser;

use Generated\Shared\Transfer\CustomerTransfer;

interface OauthCompanyUserClientInterface
{
    /**
     * Specification:
     * - Makes Zed request.
     * - Retrieves customer from access token.
     * - Executes CustomerExpanderPlugin stack.
     *
     * @api
     *
     * @param string $accessToken
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerByAccessToken(string $accessToken): CustomerTransfer;
}
