<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser\Business\Installer;

use Generated\Shared\Transfer\OauthScopeTransfer;
use Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToOauthFacadeInterface;
use Spryker\Zed\OauthCompanyUser\OauthCompanyUserConfig;

class OauthScopeInstaller implements OauthScopeInstallerInterface
{
    /**
     * @var \Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToOauthFacadeInterface
     */
    protected $oauthFacade;

    /**
     * @var \Spryker\Zed\OauthCompanyUser\OauthCompanyUserConfig
     */
    protected $oauthCompanyUserConfig;

    /**
     * @param \Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToOauthFacadeInterface $oauthFacade
     * @param \Spryker\Zed\OauthCompanyUser\OauthCompanyUserConfig $oauthCompanyUserConfig
     */
    public function __construct(
        OauthCompanyUserToOauthFacadeInterface $oauthFacade,
        OauthCompanyUserConfig $oauthCompanyUserConfig
    ) {
        $this->oauthFacade = $oauthFacade;
        $this->oauthCompanyUserConfig = $oauthCompanyUserConfig;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $companyUserScopes = $this->oauthCompanyUserConfig->getCompanyUserScopes();
        $oauthScopesTransfers = $this->getScopesByIdentifiers($companyUserScopes);

        foreach ($companyUserScopes as $companyUserScope) {
            if (!$this->hasOauthScope($companyUserScope, $oauthScopesTransfers)) {
                $oauthScopeTransfer = new OauthScopeTransfer();
                $oauthScopeTransfer->setIdentifier($companyUserScope);

                $oauthScopesTransfers[$companyUserScope] = $this->oauthFacade->saveScope($oauthScopeTransfer);
            }
        }
    }

    /**
     * @param string[] $companyUserScopes
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[] $oauthScopeTransfers
     */
    protected function getScopesByIdentifiers(array $companyUserScopes): array
    {
        $oauthScopesTransfersWithIdentifierKeys = [];
        $oauthScopesTransfers = $this->oauthFacade->getScopesByIdentifiers($companyUserScopes);

        foreach ($oauthScopesTransfers as $oauthScopeTransfer) {
            $oauthScopesIdentifier = $oauthScopeTransfer->getIdentifier();
            $oauthScopesTransfersWithIdentifierKeys[$oauthScopesIdentifier] = $oauthScopeTransfer;
        }

        return $oauthScopesTransfersWithIdentifierKeys;
    }

    /**
     * @param string $oauthScopeIdentifier
     * @param \Generated\Shared\Transfer\OauthScopeTransfer[] $oauthScopeTransfers
     *
     * @return bool
     */
    protected function hasOauthScope(string $oauthScopeIdentifier, array $oauthScopeTransfers): bool
    {
        if (isset($oauthScopeTransfers[$oauthScopeIdentifier])) {
            return true;
        }

        return false;
    }
}
