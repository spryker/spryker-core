<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Grant;

use Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;

class GrantTypeConfigurationLoader implements GrantTypeConfigurationLoaderInterface
{
    /**
     * @var array|\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthGrantTypeConfigurationProviderPluginInterface[]
     */
    protected $oauthGrantTypeConfigurationProviderPlugins;

    /**
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthGrantTypeConfigurationProviderPluginInterface[] $oauthGrantTypeConfigurationProviderPlugins
     */
    public function __construct(
        array $oauthGrantTypeConfigurationProviderPlugins
    ) {
        $this->oauthGrantTypeConfigurationProviderPlugins = $oauthGrantTypeConfigurationProviderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer|null
     */
    public function loadGrantTypeConfigurationByGrantType(OauthRequestTransfer $oauthRequestTransfer): ?OauthGrantTypeConfigurationTransfer
    {
        foreach ($this->oauthGrantTypeConfigurationProviderPlugins as $oauthGrantTypeConfigurationProviderPlugin) {
            $oauthGrantTypeConfigurationTransfer = $oauthGrantTypeConfigurationProviderPlugin->getGrantTypeConfiguration();
            if ($oauthGrantTypeConfigurationTransfer->getIdentifier() === $oauthRequestTransfer->getGrantType()) {
                return $oauthGrantTypeConfigurationTransfer;
            }
        }

        return null;
    }
}
