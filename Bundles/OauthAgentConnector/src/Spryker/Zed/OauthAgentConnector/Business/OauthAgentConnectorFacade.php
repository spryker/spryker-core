<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthAgentConnector\Business;

use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\OauthAgentConnector\Business\OauthAgentConnectorBusinessFactory getFactory()
 */
class OauthAgentConnectorFacade extends AbstractFacade implements OauthAgentConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getAgentOauthUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer
    {
        return $this->getFactory()->createCustomerProvider()->getAgentOauthUser($oauthUserTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[]
     */
    public function getScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array
    {
        return $this->getFactory()->createScopeProvider()->getScopes($oauthScopeRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function installAgentOauthScope(): void
    {
        $this->getFactory()->createAgentOauthScopeInstaller()->install();
    }
}
