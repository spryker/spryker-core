<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthRestApi\Business;

use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;

interface AuthRestApiFacadeInterface
{
    /**
     * @param OauthRequestTransfer $oauthRequestTransfer
     *
     * @return OauthResponseTransfer
     */
    public function processAccessToken(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer;
}
