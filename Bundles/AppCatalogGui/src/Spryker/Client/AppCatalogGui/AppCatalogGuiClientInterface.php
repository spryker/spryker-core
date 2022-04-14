<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AppCatalogGui;

use Generated\Shared\Transfer\AccessTokenResponseTransfer;

interface AppCatalogGuiClientInterface
{
    /**
     * Specification:
     * - Sends the request to the authentication service, based on the module configuration.
     * - Adds accessToken, expiresIn to AccessTokenResponseTransfer if the request is successful.
     * - Adds the error and the errorDescription to AccessTokenResponseTransfer.oauthResponseError if the request is failed.
     * - Returns AccessTokenResponseTransfer.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    public function requestAccessToken(): AccessTokenResponseTransfer;
}
