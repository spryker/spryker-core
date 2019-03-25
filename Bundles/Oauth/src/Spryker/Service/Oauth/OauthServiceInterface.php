<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Oauth;

use Generated\Shared\Transfer\OauthAccessTokenDataTransfer;

interface OauthServiceInterface
{
    /**
     * Specification:
     * - Extracts access token data.
     * - Decodes data from access token and formats claims.
     *
     * @api
     *
     * @param string $accessToken
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenDataTransfer
     */
    public function extractAccessTokenData(string $accessToken): OauthAccessTokenDataTransfer;
}
