<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCustomerConnector\Business;

use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\OauthCustomerConnector\Business\OauthCustomerConnectorBusinessFactory getFactory()
 */
class OauthCustomerConnectorFacade extends AbstractFacade implements OauthCustomerConnectorFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getCustomerOauthUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer
    {
        return $this->getFactory()->createCustomerProvider()->getCustomerOauthUser($oauthUserTransfer);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @return void
     */
    public function installCustomerOauthData(): void
    {
        $this->getFactory()->createInstaller()->install();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function installOauthCustomerScope(): void
    {
        $this->getFactory()
            ->createOauthCustomerScopeInstaller()
            ->install();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @return string
     */
    public function getCustomerOauthClientSecret(): string
    {
        return $this->getFactory()->getModuleConfig()->getClientSecret();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @return string
     */
    public function getCustomerOauthClientIdentifier(): string
    {
        return $this->getFactory()->getModuleConfig()->getClientId();
    }
}
