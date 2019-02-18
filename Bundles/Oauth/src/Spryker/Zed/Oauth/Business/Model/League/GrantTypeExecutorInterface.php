<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League;

use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use League\OAuth2\Server\Grant\AbstractGrant;

interface GrantTypeExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     * @param \League\OAuth2\Server\Grant\AbstractGrant $grantType
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function processAccessTokenRequest(OauthRequestTransfer $oauthRequestTransfer, AbstractGrant $grantType): OauthResponseTransfer;
}
