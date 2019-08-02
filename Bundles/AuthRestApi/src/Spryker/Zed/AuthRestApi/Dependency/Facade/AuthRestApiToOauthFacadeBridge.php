<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthRestApi\Dependency\Facade;

class AuthRestApiToOauthFacadeBridge implements AuthRestApiToOauthFacadeInterface
{
    /**
     * @var \Spryker\Zed\Oauth\Business\OauthFacadeInterface
     */
    protected $oauthFacade;

    /**
     * @param \Spryker\Zed\Oauth\Business\OauthFacadeInterface $oauthFacade
     */
    public function __construct($oauthFacade)
    {
        $this->oauthFacade = $oauthFacade;
    }
    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
 *
 * @return \Generated\Shared\Transfer\OauthResponseTransfer
 */
    public function processAccessTokenRequest(\Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer): \Generated\Shared\Transfer\OauthResponseTransfer
    {
        return $this->oauthFacade->processAccessTokenRequest($oauthRequestTransfer);
    }
}
