<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCodeFlow\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\GlueAuthenticationRequestContextTransfer;
use Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthCodeFlow\Business\Builders\UserAuthCodeGrantTypeBuilder;
use Spryker\Zed\OauthCodeFlow\OauthCodeFlowConfig;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRequestGrantTypeConfigurationProviderPluginInterface;

/**
 * @method \Spryker\Zed\OauthCodeFlow\OauthCodeFlowConfig getConfig()
 */
class UserAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin extends AbstractPlugin implements OauthRequestGrantTypeConfigurationProviderPluginInterface
{
    /**
     * @uses \Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\ApplicationIdentifierRequestBuilderPlugin::GLUE_BACKEND_API_APPLICATION
     *
     * @var string
     */
    protected const GLUE_BACKEND_API_APPLICATION = 'GLUE_BACKEND_API_APPLICATION';

    /**
     * {@inheritDoc}
     *  - Checks whether the requested OAuth grant type equals to {@link \Spryker\Zed\OauthCodeFlow\OauthCodeFlowConfig::GRANT_TYPE_AUTHORIZATION_CODE}.
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
            $oauthRequestTransfer->getGrantType() === OauthCodeFlowConfig::GRANT_TYPE_AUTHORIZATION_CODE &&
            $glueAuthenticationRequestContextTransfer->getRequestApplication() === static::GLUE_BACKEND_API_APPLICATION
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
            ->setBuilderFullyQualifiedClassName(UserAuthCodeGrantTypeBuilder::class);
    }
}
