<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League;

use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use League\OAuth2\Server\Grant\GrantTypeInterface;
use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantInterface;

interface GrantTypeExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     * @param \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantInterface $grantType
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function processAccessTokenRequest(OauthRequestTransfer $oauthRequestTransfer, GrantInterface $grantType): OauthResponseTransfer;
}
