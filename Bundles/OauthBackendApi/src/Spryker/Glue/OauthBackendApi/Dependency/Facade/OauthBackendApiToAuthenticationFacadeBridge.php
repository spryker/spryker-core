<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthBackendApi\Dependency\Facade;

use Generated\Shared\Transfer\GlueAuthenticationRequestTransfer;
use Generated\Shared\Transfer\GlueAuthenticationResponseTransfer;

class OauthBackendApiToAuthenticationFacadeBridge implements OauthBackendApiToAuthenticationFacadeInterface
{
    /**
     * @var \Spryker\Zed\Authentication\Business\AuthenticationFacadeInterface
     */
    protected $authenticationFacade;

    /**
     * @param \Spryker\Zed\Authentication\Business\AuthenticationFacadeInterface $authenticationFacade
     */
    public function __construct($authenticationFacade)
    {
        $this->authenticationFacade = $authenticationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueAuthenticationResponseTransfer
     */
    public function authenticate(GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer): GlueAuthenticationResponseTransfer
    {
        return $this->authenticationFacade->authenticate($glueAuthenticationRequestTransfer);
    }
}
