<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthUserConnector\Dependency\Facade;

use Generated\Shared\Transfer\OauthClientTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;

class OauthUserConnectorToOauthFacadeBridge implements OauthUserConnectorToOauthFacadeInterface
{
    /**
     * @var \Spryker\Zed\Oauth\Business\OauthFacadeInterface
     */
    protected $oauthFacade;

    /**
     * @param \Spryker\Zed\Oauth\Business\OauthFacadeInterface $oauthFacade
     */
    public function __construct($oauthFacade)
    {
        $this->oauthFacade = $oauthFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthScopeTransfer $oauthScopeTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer
     */
    public function saveScope(OauthScopeTransfer $oauthScopeTransfer): OauthScopeTransfer
    {
        return $this->oauthFacade->saveScope($oauthScopeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthScopeTransfer $oauthScopeTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer|null
     */
    public function findScopeByIdentifier(OauthScopeTransfer $oauthScopeTransfer): ?OauthScopeTransfer
    {
        return $this->oauthFacade->findScopeByIdentifier($oauthScopeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthClientTransfer $oauthClientTransfer
     *
     * @return \Generated\Shared\Transfer\OauthClientTransfer|null
     */
    public function findClientByIdentifier(OauthClientTransfer $oauthClientTransfer): ?OauthClientTransfer
    {
        return $this->oauthFacade->findClientByIdentifier($oauthClientTransfer);
    }

    /**
     * @param array<string> $userScopes
     *
     * @return array<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    public function getScopesByIdentifiers(array $userScopes): array
    {
        return $this->oauthFacade->getScopesByIdentifiers($userScopes);
    }
}
