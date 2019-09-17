<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OauthResponseTransfer;

interface PostAuthPluginInterface
{
    /**
     * Specification:
     * - Executes after customer is logged in via REST API.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthResponseTransfer $oauthResponseTransfer
     *
     * @return void
     */
    public function postAuth(OauthResponseTransfer $oauthResponseTransfer): void;
}
