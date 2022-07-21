<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthenticationOauth\Communication\Controller;

use Generated\Shared\Transfer\GlueAuthenticationRequestTransfer;
use Generated\Shared\Transfer\GlueAuthenticationResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\AuthenticationOauth\Business\AuthenticationOauthFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueAuthenticationResponseTransfer
     */
    public function authenticateAction(GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer): GlueAuthenticationResponseTransfer
    {
        return $this->getFacade()->authenticate($glueAuthenticationRequestTransfer);
    }
}
