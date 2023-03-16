<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouse\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\GlueAuthenticationRequestContextTransfer;
use Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRequestGrantTypeConfigurationProviderPluginInterface;
use Spryker\Zed\OauthWarehouse\Business\League\Grant\WarehouseTokenGrantType;
use Spryker\Zed\OauthWarehouse\OauthWarehouseConfig;

/**
 * @method \Spryker\Zed\OauthWarehouse\OauthWarehouseConfig getConfig()
 * @method \Spryker\Zed\OauthWarehouse\Business\OauthWarehouseFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthWarehouse\Communication\OauthWarehouseCommunicationFactory getFactory()
 */
class WarehouseOauthRequestGrantTypeConfigurationProviderPlugin extends AbstractPlugin implements OauthRequestGrantTypeConfigurationProviderPluginInterface
{
    /**
     * @uses \Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\ApplicationIdentifierRequestBuilderPlugin::GLUE_BACKEND_API_APPLICATION
     *
     * @var string
     */
    protected const GLUE_BACKEND_API_APPLICATION = 'GLUE_BACKEND_API_APPLICATION';

    /**
     * {@inheritDoc}
     *  - Checks whether the requested OAuth grant type equals to {@link \Spryker\Zed\OauthWarehouse\OauthWarehouseConfig::WAREHOUSE_GRANT_TYPE}.
     *  - Checks whether the requested application context equals to GlueBackendApiApplication.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     * @param \Generated\Shared\Transfer\GlueAuthenticationRequestContextTransfer $glueAuthenticationRequestContextTransfer
     *
     * @return bool
     */
    public function isApplicable(
        OauthRequestTransfer $oauthRequestTransfer,
        GlueAuthenticationRequestContextTransfer $glueAuthenticationRequestContextTransfer
    ): bool {
        return (
            $oauthRequestTransfer->getGrantType() === OauthWarehouseConfig::WAREHOUSE_GRANT_TYPE &&
            $glueAuthenticationRequestContextTransfer->getRequestApplication() === static::GLUE_BACKEND_API_APPLICATION
        );
    }

    /**
     * {@inheritDoc}
     * - Returns configuration of Warehouse GrantType.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer
     */
    public function getGrantTypeConfiguration(): OauthGrantTypeConfigurationTransfer
    {
        return (new OauthGrantTypeConfigurationTransfer())
            ->setIdentifier(OauthWarehouseConfig::WAREHOUSE_GRANT_TYPE)
            ->setFullyQualifiedClassName(WarehouseTokenGrantType::class);
    }
}
