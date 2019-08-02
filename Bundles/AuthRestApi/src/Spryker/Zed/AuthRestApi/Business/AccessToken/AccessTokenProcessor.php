<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthRestApi\Business\AccessToken;

use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Spryker\Zed\AuthRestApi\Dependency\Facade\AuthRestApiToOauthFacadeInterface;

class AccessTokenProcessor implements AccessTokenProcessorInterface
{
    /**
     * @var \Spryker\Zed\AuthRestApi\Dependency\Facade\AuthRestApiToOauthFacadeInterface
     */
    protected $oauthFacade;

    /**
     * @param \Spryker\Zed\AuthRestApi\Dependency\Facade\AuthRestApiToOauthFacadeInterface $oauthFacade
     */
    public function __construct(AuthRestApiToOauthFacadeInterface $oauthFacade)
    {
        $this->oauthFacade = $oauthFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function processAccessToken(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer
    {
        $oauthResponseTransfer = $this->oauthFacade->processAccessTokenRequest($oauthRequestTransfer);
        $oauthResponseTransfer->setAnonymousCustomerReference($oauthRequestTransfer->getCustomerReference());
        // execute


        return $oauthResponseTransfer;
    }
}
