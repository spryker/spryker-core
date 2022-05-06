<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthenticationOauth\Business\Processor;

use Generated\Shared\Transfer\GlueAuthenticationRequestTransfer;
use Generated\Shared\Transfer\GlueAuthenticationResponseTransfer;
use Spryker\Zed\AuthenticationOauth\Business\Dependency\Facade\AuthenticationOauthToOauthFacadeInterface;

class AuthenticationOauth implements AuthenticationOauthInterface
{
    /**
     * @var \Spryker\Zed\AuthenticationOauth\Business\Dependency\Facade\AuthenticationOauthToOauthFacadeInterface
     */
    protected $oauthFacade;

    /**
     * @param \Spryker\Zed\AuthenticationOauth\Business\Dependency\Facade\AuthenticationOauthToOauthFacadeInterface $oauthFacade
     */
    public function __construct(AuthenticationOauthToOauthFacadeInterface $oauthFacade)
    {
        $this->oauthFacade = $oauthFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueAuthenticationResponseTransfer
     */
    public function authenticate(GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer): GlueAuthenticationResponseTransfer
    {
        $oauthRequestTransfer = $glueAuthenticationRequestTransfer->getOauthRequestOrFail();
        $oauthRequestTransfer->setGlueAuthenticationRequestContext($glueAuthenticationRequestTransfer->getRequestContextOrFail());

        $oauthResponseTransfer = $this->oauthFacade->processAccessTokenRequest($oauthRequestTransfer);

        $glueAuthenticationResponseTransfer = (new GlueAuthenticationResponseTransfer())
            ->setOauthResponse($oauthResponseTransfer);

        return $glueAuthenticationResponseTransfer;
    }
}
