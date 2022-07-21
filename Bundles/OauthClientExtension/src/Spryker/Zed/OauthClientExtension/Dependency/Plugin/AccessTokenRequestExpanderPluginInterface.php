<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthClientExtension\Dependency\Plugin;

use Generated\Shared\Transfer\AccessTokenRequestTransfer;

/**
 * Plugin interface is used to expand the `AccessTokenRequestTransfer` with additional data.
 *
 * Executes on access token receiving.
 */
interface AccessTokenRequestExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands access token request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenRequestTransfer
     */
    public function expand(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenRequestTransfer;
}
