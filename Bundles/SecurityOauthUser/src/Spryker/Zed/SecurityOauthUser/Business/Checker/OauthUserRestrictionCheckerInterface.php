<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Business\Checker;

use Generated\Shared\Transfer\OauthUserRestrictionRequestTransfer;
use Generated\Shared\Transfer\OauthUserRestrictionResponseTransfer;

interface OauthUserRestrictionCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\OauthUserRestrictionRequestTransfer $oauthUserRestrictionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserRestrictionResponseTransfer
     */
    public function isOauthUserRestricted(
        OauthUserRestrictionRequestTransfer $oauthUserRestrictionRequestTransfer
    ): OauthUserRestrictionResponseTransfer;
}
