<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Dependency\Facade;

use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;

class TaxAppToOauthClientFacadeBridge implements TaxAppToOauthClientFacadeInterface
{
    /**
     * @var \Spryker\Zed\OauthClient\Business\OauthClientFacadeInterface
     */
    protected $oauthClientFacade;

    /**
     * @param \Spryker\Zed\OauthClient\Business\OauthClientFacadeInterface $oauthClientFacade
     */
    public function __construct($oauthClientFacade)
    {
        $this->oauthClientFacade = $oauthClientFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    public function getAccessToken(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenResponseTransfer
    {
        return $this->oauthClientFacade->getAccessToken($accessTokenRequestTransfer);
    }
}
