<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Grant;

use Generated\Shared\Transfer\OauthGrantConfigurationTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;

class GrantConfigurationLoader implements GrantConfigurationLoaderInterface
{
    /**
     * @var array|\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthGrantConfigurationProviderPluginInterface[]
     */
    protected $oauthGrantTypeConfigurationProviderPlugins;

    /**
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthGrantConfigurationProviderPluginInterface[] $oauthGrantTypeConfigurationProviderPlugins
     */
    public function __construct(
        array $oauthGrantTypeConfigurationProviderPlugins
    ) {
        $this->oauthGrantTypeConfigurationProviderPlugins = $oauthGrantTypeConfigurationProviderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthGrantConfigurationTransfer|null
     */
    public function loadGrantConfigurationByGrantType(OauthRequestTransfer $oauthRequestTransfer): ?OauthGrantConfigurationTransfer
    {
        foreach ($this->oauthGrantTypeConfigurationProviderPlugins as $oauthGrantConfigurationProviderPlugin) {
            $oauthGrantConfigurationTransfer = $oauthGrantConfigurationProviderPlugin->getGrantConfiguration();
            if ($oauthGrantConfigurationTransfer->getIdentifier() === $oauthRequestTransfer->getGrantType()) {
                return $oauthGrantConfigurationTransfer;
            }
        }

        return null;
    }
}
