<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Repositories;

use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Spryker\Zed\Oauth\Business\Model\League\Entities\ScopeEntity;
use Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface;

class ScopeRepository implements ScopeRepositoryInterface
{
    /**
     * @var \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface
     */
    protected $oauthRepository;

    /**
     * @var \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthScopeProviderPluginInterface[]
     */
    protected $scopeProviderPlugins;

    /**
     * @param \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface $oauthRepository
     * @param array $scopeProviderPlugins
     */
    public function __construct(
        OauthRepositoryInterface $oauthRepository,
        array $scopeProviderPlugins = []
    ) {
        $this->oauthRepository = $oauthRepository;
        $this->scopeProviderPlugins = $scopeProviderPlugins;
    }

    /**
     * Return information about a scope.
     *
     * @param string $identifier The scope identifier
     *
     * @return \League\OAuth2\Server\Entities\ScopeEntityInterface
     */
    public function getScopeEntityByIdentifier($identifier)
    {
        $scopeEntityTransfer = $this->oauthRepository->findScopeByIdentifier($identifier);
        if (!$scopeEntityTransfer) {
            return null;
        }
        $scope = new ScopeEntity();
        $scope->setIdentifier($identifier);

        return $scope;
    }

    /**
     * Given a client, grant type and optional user identifier validate the set of scopes requested are valid and optionally
     * append additional scopes or remove requested scopes.
     *
     * @param \League\OAuth2\Server\Entities\ScopeEntityInterface[] $scopes
     * @param string $grantType
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $clientEntity
     * @param null|string $userIdentifier
     *
     * @return \League\OAuth2\Server\Entities\ScopeEntityInterface[]
     */
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ) {
        $oauthScopeRequestTransfer = $this->mapOauthScopeRequestTransfer($scopes, $grantType, $clientEntity, $userIdentifier);
        $providedScopes = $this->getProvidedScopes($oauthScopeRequestTransfer);
        return $this->mapScopeEntities($providedScopes);
    }

    /**
     * @param array $scopes
     * @param string $grantType
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $clientEntity
     * @param string|null $userIdentifier
     *
     * @return \Generated\Shared\Transfer\OauthScopeRequestTransfer
     */
    protected function mapOauthScopeRequestTransfer(
        array $scopes,
        string $grantType,
        ClientEntityInterface $clientEntity,
        ?string $userIdentifier = null
    ): OauthScopeRequestTransfer {

        $oauthScopeRequestTransfer = (new OauthScopeRequestTransfer())
            ->setGrantType($grantType)
            ->setClientId($clientEntity->getIdentifier())
            ->setClientName($clientEntity->getName());

        if ($userIdentifier) {
            $oauthScopeRequestTransfer->setUserIdentifier($userIdentifier);
        }

        foreach ($scopes as $scope) {
            $authScopeTransfer = new OauthScopeTransfer();
            $authScopeTransfer->setIdentifier($scope->getIdentifier());
            $oauthScopeRequestTransfer->addScope($authScopeTransfer);
        }

        return $oauthScopeRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[]
     */
    protected function getProvidedScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array
    {
        $providedScopes = [];
        foreach ($this->scopeProviderPlugins as $scopeProviderPlugin) {
            if (!$scopeProviderPlugin->accept($oauthScopeRequestTransfer)) {
                continue;
            }

            $providedScopes = $scopeProviderPlugin->getScopes($oauthScopeRequestTransfer);
        }
        return $providedScopes;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthScopeTransfer[] $providedScopes
     *
     * @return array
     */
    protected function mapScopeEntities($providedScopes): array
    {
        $scopes = [];
        foreach ($providedScopes as $oauthScopeTransfer) {
            $scope = new ScopeEntity();
            $scope->setIdentifier($oauthScopeTransfer->getIdentifier());
            $scopes[] = $scope;
        }
        return $scopes;
    }
}
