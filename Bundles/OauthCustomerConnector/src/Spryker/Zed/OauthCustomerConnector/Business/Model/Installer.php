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
     * @return void
     */
    public function install(): void
    {
        foreach ($this->oauthCustomerConnectorConfig->getCustomerScopes() as $scope) {
            $oauthScopeTransfer = new OauthScopeTransfer();
            $oauthScopeTransfer->setIdentifier($scope);

            if (!$this->isExistOauthScope($oauthScopeTransfer)) {
                $this->oauthFacade->saveScope($oauthScopeTransfer);
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
     * @param \Generated\Shared\Transfer\OauthScopeTransfer $oauthScopeTransfer
     *
     * @return bool
     */
    protected function isExistOauthScope(OauthScopeTransfer $oauthScopeTransfer): bool
    {
        return $this->oauthFacade->findScopeByIdentifier($oauthScopeTransfer) !== null;
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
