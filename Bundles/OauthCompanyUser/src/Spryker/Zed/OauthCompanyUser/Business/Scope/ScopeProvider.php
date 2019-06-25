<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser\Business\Scope;

use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Spryker\Zed\OauthCompanyUser\OauthCompanyUserConfig;

class ScopeProvider implements ScopeProviderInterface
{
    /**
     * @var \Spryker\Zed\OauthCompanyUser\OauthCompanyUserConfig
     */
    protected $oauthCompanyUserConfig;

    /**
     * @param \Spryker\Zed\OauthCompanyUser\OauthCompanyUserConfig $oauthCompanyUserConfig
     */
    public function __construct(OauthCompanyUserConfig $oauthCompanyUserConfig)
    {
        $this->oauthCompanyUserConfig = $oauthCompanyUserConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[]
     */
    public function getScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array
    {
        $scopes = (array)$oauthScopeRequestTransfer->getDefaultScopes();
        foreach ($this->oauthCompanyUserConfig->getCompanyUserScopes() as $scope) {
            $oauthScopeTransfer = new OauthScopeTransfer();
            $oauthScopeTransfer->setIdentifier($scope);
            $scopes[] = $oauthScopeTransfer;
        }

        return $scopes;
    }
}
