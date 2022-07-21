<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Grant;

use Generated\Shared\Transfer\GlueAuthenticationRequestContextTransfer;
use Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;

class OauthGrantTypeConfigurationLoader implements OauthGrantTypeConfigurationLoaderInterface
{
    /**
     * @var array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRequestGrantTypeConfigurationProviderPluginInterface>
     */
    protected $oauthRequestGrantTypeConfigurationProviderPlugins = [];

    /**
     * @param array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRequestGrantTypeConfigurationProviderPluginInterface> $oauthRequestGrantTypeConfigurationProviderPlugins
     */
    public function __construct(array $oauthRequestGrantTypeConfigurationProviderPlugins)
    {
        $this->oauthRequestGrantTypeConfigurationProviderPlugins = $oauthRequestGrantTypeConfigurationProviderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     * @param \Generated\Shared\Transfer\GlueAuthenticationRequestContextTransfer $glueAuthenticationRequestContextTransfer
     *
     * @return \Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer|null
     */
    public function loadGrantTypeConfiguration(
        OauthRequestTransfer $oauthRequestTransfer,
        GlueAuthenticationRequestContextTransfer $glueAuthenticationRequestContextTransfer
    ): ?OauthGrantTypeConfigurationTransfer {
        foreach ($this->oauthRequestGrantTypeConfigurationProviderPlugins as $oauthRequestGrantTypeConfigurationProviderPlugin) {
            $oauthGrantTypeConfigurationTransfer = $oauthRequestGrantTypeConfigurationProviderPlugin->getGrantTypeConfiguration();

            if ($oauthRequestGrantTypeConfigurationProviderPlugin->isApplicable($oauthRequestTransfer, $glueAuthenticationRequestContextTransfer)) {
                return $oauthGrantTypeConfigurationTransfer;
            }
        }

        return null;
    }
}
