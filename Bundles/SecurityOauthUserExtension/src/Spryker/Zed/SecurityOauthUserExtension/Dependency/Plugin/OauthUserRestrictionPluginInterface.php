<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OauthUserRestrictionRequestTransfer;
use Generated\Shared\Transfer\OauthUserRestrictionResponseTransfer;

/**
 * Use this plugin to restrict users to authenticate via Oauth.
 */
interface OauthUserRestrictionPluginInterface
{
    /**
     * Specification:
     * - Checks if the user is restricted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserRestrictionRequestTransfer $oauthUserRestrictionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserRestrictionResponseTransfer
     */
    public function isRestricted(OauthUserRestrictionRequestTransfer $oauthUserRestrictionRequestTransfer): OauthUserRestrictionResponseTransfer;
}
