<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Dependency\Plugin;

use Generated\Shared\Transfer\OauthUserTransfer;

interface UserProviderPluginInterface
{
    /**
     * @api
     *
     * Returns oauth user based on data provided by authorizing client
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer;

    /**
     * @api
     *
     * Method which should return if it can handle oauth client request
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return bool
     */
    public function accept(OauthUserTransfer $oauthUserTransfer): bool;
}
