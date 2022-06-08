<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppCatalogGui\Business;

use Generated\Shared\Transfer\AccessTokenResponseTransfer;

/**
 * @method \Spryker\Zed\AppCatalogGui\Business\AppCatalogGuiBusinessFactory getFactory()
 */
interface AppCatalogGuiFacadeInterface
{
    /**
     * Specification:
     * - Does nothing with the AccessTokenResponseTransfer if the AccessTokenResponseTransfer.isSuccessful is `true`.
     * - Adds general translatable message to AccessTokenResponseTransfer.errorMessage if the AccessTokenResponseTransfer.isSuccessful is `false`.
     * - Logs the error if the AccessTokenResponseTransfer.isSuccessful is `false`.
     * - Returns AccessTokenResponseTransfer.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    public function requestAccessToken(): AccessTokenResponseTransfer;
}
