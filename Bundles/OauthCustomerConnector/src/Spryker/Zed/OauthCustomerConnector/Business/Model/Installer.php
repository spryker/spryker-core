<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCustomerConnector\Business\Model;

use Generated\Shared\Transfer\OauthClientTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Spryker\Zed\OauthCustomerConnector\Dependency\Facade\OauthCustomerConnectorToOauthFacadeInterface;
use Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig;

/**
 * @deprecated Will be removed in the next major.
 */
class Installer implements InstallerInterface
{
    /**
     * @var \Spryker\Zed\OauthCustomerConnector\Dependency\Facade\OauthCustomerConnectorToOauthFacadeInterface
     */
    protected $oauthFacade;

    /**
     * @var \Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig
     */
    protected $oauthCustomerConnectorConfig;

    /**
     * @param \Spryker\Zed\OauthCustomerConnector\Dependency\Facade\OauthCustomerConnectorToOauthFacadeInterface $oauthFacade
     * @param \Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig $oauthCustomerConnectorConfig
     */
    public function __construct(
        OauthCustomerConnectorToOauthFacadeInterface $oauthFacade,
        OauthCustomerConnectorConfig $oauthCustomerConnectorConfig
    ) {
        $this->oauthFacade = $oauthFacade;
        $this->oauthCustomerConnectorConfig = $oauthCustomerConnectorConfig;
    }

    /**
     * @deprecated Will be removed in the next major.
     *
     * @return void
     */
    public function install(): void
    {
        $customerScopes = $this->oauthCustomerConnectorConfig->getCustomerScopes();
        $oauthScopesTransfers = $this->getScopesByIdentifiers($customerScopes);

        foreach ($customerScopes as $customerScope) {
            if (!$this->isExistOauthScope($customerScope, $oauthScopesTransfers)) {
                $oauthScopeTransfer = new OauthScopeTransfer();
                $oauthScopeTransfer->setIdentifier($customerScope);

                $oauthScopesTransfers[$customerScope] = $this->oauthFacade->saveScope($oauthScopeTransfer);
            }
        }

        $oauthClientTransfer = new OauthClientTransfer();
        $oauthClientTransfer->setIdentifier(
            $this->oauthCustomerConnectorConfig->getClientId()
        );

        if (!$this->isExistOauthClient($oauthClientTransfer)) {
            $oauthClientTransfer->setSecret(
                password_hash($this->oauthCustomerConnectorConfig->getClientSecret(), PASSWORD_BCRYPT)
            );
            $oauthClientTransfer->setIsConfidential(true);
            $oauthClientTransfer->setName('Customer client');

            $this->oauthFacade->saveClient($oauthClientTransfer);
        }
    }

    /**
     * @param string[] $customerScopes
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[] $oauthScopeTransfers
     */
    protected function getScopesByIdentifiers(array $customerScopes): array
    {
        $oauthScopesTransfersWithIdentifierKeys = [];
        $oauthScopesTransfers = $this->oauthFacade->getScopesByIdentifiers($customerScopes);

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
    protected function isExistOauthScope(string $oauthScopeIdentifier, array $oauthScopeTransfers): bool
    {
        if (isset($oauthScopeTransfers[$oauthScopeIdentifier])) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthClientTransfer $oauthClientTransfer
     *
     * @return bool
     */
    protected function isExistOauthClient(OauthClientTransfer $oauthClientTransfer): bool
    {
        return $this->oauthFacade->findClientByIdentifier($oauthClientTransfer) !== null;
    }
}
