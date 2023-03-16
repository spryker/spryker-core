<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouse\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthScopeProviderPluginInterface;
use Spryker\Zed\OauthWarehouse\OauthWarehouseConfig;

/**
 * @method \Spryker\Zed\OauthWarehouse\Business\OauthWarehouseFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthWarehouse\OauthWarehouseConfig getConfig()
 * @method \Spryker\Zed\OauthWarehouse\Communication\OauthWarehouseCommunicationFactory getFactory()
 */
class WarehouseOauthScopeProviderPlugin extends AbstractPlugin implements OauthScopeProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks whether the grant type is {@link \Spryker\Zed\OauthWarehouse\OauthWarehouseConfig::WAREHOUSE_GRANT_TYPE}.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return bool
     */
    public function accept(OauthScopeRequestTransfer $oauthScopeRequestTransfer): bool
    {
        return $oauthScopeRequestTransfer->getGrantType() === OauthWarehouseConfig::WAREHOUSE_GRANT_TYPE;
    }

    /**
     * {@inheritDoc}
     * - Returns warehouse scopes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return list<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    public function getScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array
    {
        return $this->getFacade()->getScopes($oauthScopeRequestTransfer);
    }
}
