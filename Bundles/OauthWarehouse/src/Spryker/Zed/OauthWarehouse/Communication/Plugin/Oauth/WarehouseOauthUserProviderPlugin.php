<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouse\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\OauthUserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserProviderPluginInterface;
use Spryker\Zed\OauthWarehouse\OauthWarehouseConfig;

/**
 * @method \Spryker\Zed\OauthWarehouse\Communication\OauthWarehouseCommunicationFactory getFactory()
 * @method \Spryker\Zed\OauthWarehouse\Business\OauthWarehouseFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthWarehouse\OauthWarehouseConfig getConfig()
 */
class WarehouseOauthUserProviderPlugin extends AbstractPlugin implements OauthUserProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if Warehouse GrantType is provided, false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return bool
     */
    public function accept(OauthUserTransfer $oauthUserTransfer): bool
    {
        return $oauthUserTransfer->getGrantType() === OauthWarehouseConfig::WAREHOUSE_GRANT_TYPE;
    }

    /**
     * {@inheritDoc}
     * - Retrieves warehouse user if `OauthUserTransfer.idWarehouse` provided.
     * - Expands the `OauthUserTransfer` if warehouse user exists.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer
    {
        return $this->getFacade()->getOauthWarehouseUser($oauthUserTransfer);
    }
}
