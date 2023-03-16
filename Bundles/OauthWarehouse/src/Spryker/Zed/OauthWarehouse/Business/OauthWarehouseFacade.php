<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouse\Business;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\OauthWarehouse\Business\OauthWarehouseBusinessFactory getFactory()
 */
class OauthWarehouseFacade extends AbstractFacade implements OauthWarehouseFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return list<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    public function getScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array
    {
        return $this->getFactory()
            ->createScopeProvider()
            ->getScopes($oauthScopeRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function installWarehouseOauthData(): void
    {
        $this->getFactory()
            ->createOauthScopeInstaller()
            ->install();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return bool
     */
    public function authorize(AuthorizationRequestTransfer $authorizationRequestTransfer): bool
    {
        return $this->getFactory()
            ->createWarehouseTokenAuthorizationChecker()
            ->authorize($authorizationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getOauthWarehouseUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer
    {
        return $this->getFactory()
            ->createWarehouseUserProvider()
            ->getOauthWarehouseUser($oauthUserTransfer);
    }
}
