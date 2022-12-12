<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCodeFlow\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\GlueAuthenticationRequestContextTransfer;
use Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Zed\OauthCodeFlow\Business\Builders\CustomerAuthCodeGrantTypeBuilder;
use Spryker\Zed\OauthCodeFlow\OauthCodeFlowConfig;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRequestGrantTypeConfigurationProviderPluginInterface;

/**
 * @method \Spryker\Zed\OauthCodeFlow\OauthCodeFlowConfig getConfig()
 * @method \Spryker\Zed\OauthCodeFlow\Business\OauthFacadeInterface getFacade()
 */
class CustomerAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin extends AbstractPlugin implements OauthRequestGrantTypeConfigurationProviderPluginInterface
{
    /**
     * @uses \Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueApplication\ApplicationIdentifierRequestBuilderPlugin::GLUE_STOREFRONT_API_APPLICATION
     *
     * @var string
     */
    protected const GLUE_STOREFRONT_API_APPLICATION = 'GLUE_STOREFRONT_API_APPLICATION';

    /**
     * {@inheritDoc}
     *  - Checks whether the requested OAuth grant type equals to {@link \Spryker\Zed\OauthCodeFlow\OauthCodeFlowConfig::GRANT_TYPE_AUTHORIZATION_CODE}.
     *  - Checks whether the requested application context equals to GlueStorefrontApiApplication.
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
            $oauthRequestTransfer->getGrantType() === OauthCodeFlowConfig::GRANT_TYPE_AUTHORIZATION_CODE &&
            $glueAuthenticationRequestContextTransfer->getRequestApplication() === static::GLUE_STOREFRONT_API_APPLICATION
        );
    }

    /**
     * {@inheritDoc}
     *  - Returns configuration of authorization code GrantType.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer
     */
    public function getGrantTypeConfiguration(): OauthGrantTypeConfigurationTransfer
    {
        return (new OauthGrantTypeConfigurationTransfer())
            ->setIdentifier(OauthCodeFlowConfig::GRANT_TYPE_AUTHORIZATION_CODE)
            ->setBuilderFullyQualifiedClassName(CustomerAuthCodeGrantTypeBuilder::class);
    }
}
