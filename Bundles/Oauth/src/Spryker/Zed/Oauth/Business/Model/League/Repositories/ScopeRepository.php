<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Repositories;

use Generated\Shared\Transfer\OauthScopeFindTransfer;
use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use Spryker\Zed\Oauth\Business\Model\League\Entities\ScopeEntity;
use Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface;

class ScopeRepository implements ScopeRepositoryInterface
{
    /**
     * @var \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface
     */
    protected $oauthRepository;

    /**
     * @var array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthScopeProviderPluginInterface>
     */
    protected $scopeProviderPlugins;

    /**
     * @var array<\Spryker\Glue\OauthExtension\Dependency\Plugin\ScopeFinderPluginInterface>
     */
    protected $scopeFinderPlugins;

    /**
     * @param \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface $oauthRepository
     * @param array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthScopeProviderPluginInterface> $scopeProviderPlugins
     * @param array<\Spryker\Glue\OauthExtension\Dependency\Plugin\ScopeFinderPluginInterface> $scopeFinderPlugins
     */
    public function __construct(
        OauthRepositoryInterface $oauthRepository,
        array $scopeProviderPlugins = [],
        array $scopeFinderPlugins = []
    ) {
        $this->oauthRepository = $oauthRepository;
        $this->scopeProviderPlugins = $scopeProviderPlugins;
        $this->scopeFinderPlugins = $scopeFinderPlugins;
    }

    /**
     * Return information about a scope.
     *
     * @param string $identifier The scope identifier
     * @param string|null $applicationName
     *
     * @return \League\OAuth2\Server\Entities\ScopeEntityInterface|null
     */
    public function getScopeEntityByIdentifier($identifier, ?string $applicationName = null)
    {
        foreach ($this->scopeFinderPlugins as $scopeFinderPlugin) {
            $oauthScopeFindTransfer = (new OauthScopeFindTransfer())->setIdentifier($identifier)->setApplicationName($applicationName);

            if ($scopeFinderPlugin->isServing($oauthScopeFindTransfer) && $scopeFinderPlugin->findScope($oauthScopeFindTransfer)) {
                return $this->createScopeEntity($identifier);
            }
        }

        $scopeEntityTransfer = $this->oauthRepository->findScopeByIdentifier($identifier);
        if (!$scopeEntityTransfer) {
            return null;
        }

        return $this->createScopeEntity($identifier);
    }

    /**
     * Given a client, grant type and optional user identifier validate the set of scopes requested are valid and optionally
     * append additional scopes or remove requested scopes.
     *
     * @param array<\League\OAuth2\Server\Entities\ScopeEntityInterface> $scopes
     * @param string $grantType
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $clientEntity
     * @param string|null $userIdentifier
     * @param string|null $applicationName
     *
     * @return array<\League\OAuth2\Server\Entities\ScopeEntityInterface>
     */
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null,
        ?string $applicationName = null
    ) {
        $oauthScopeRequestTransfer = $this->mapOauthScopeRequestTransfer($scopes, $grantType, $clientEntity, $userIdentifier, $applicationName);
        $providedScopes = $this->getProvidedScopes($oauthScopeRequestTransfer);

        return $this->mapScopeEntities($providedScopes);
    }

    /**
     * @param string $scopeIdentifier
     *
     * @return \Spryker\Zed\Oauth\Business\Model\League\Entities\ScopeEntity
     */
    public function createScopeEntity(string $scopeIdentifier): ScopeEntity
    {
        $scope = new ScopeEntity();
        $scope->setIdentifier($scopeIdentifier);

        return $scope;
    }

    /**
     * @param array $scopes
     * @param string $grantType
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $clientEntity
     * @param string|null $userIdentifier
     * @param string|null $applicationName
     *
     * @return \Generated\Shared\Transfer\OauthScopeRequestTransfer
     */
    protected function mapOauthScopeRequestTransfer(
        array $scopes,
        string $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null,
        ?string $applicationName = null
    ): OauthScopeRequestTransfer {
        $oauthScopeRequestTransfer = (new OauthScopeRequestTransfer())
            ->setGrantType($grantType)
            ->setClientId($clientEntity->getIdentifier())
            ->setClientName($clientEntity->getName())
            ->setRequestApplication($applicationName);

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
     * @return array<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    protected function getProvidedScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array
    {
        $providedScopes = [];
        foreach ($this->scopeProviderPlugins as $scopeProviderPlugin) {
            if (!$scopeProviderPlugin->accept($oauthScopeRequestTransfer)) {
                continue;
            }

            $providedScopes[] = $scopeProviderPlugin->getScopes($oauthScopeRequestTransfer);
        }

        return $providedScopes ? array_merge(...$providedScopes) : [];
    }

    /**
     * @param array<\Generated\Shared\Transfer\OauthScopeTransfer> $providedScopes
     *
     * @return array
     */
    protected function mapScopeEntities($providedScopes): array
    {
        $scopes = [];
        foreach ($providedScopes as $oauthScopeTransfer) {
            $scope = new ScopeEntity();
            $scope->setIdentifier($oauthScopeTransfer->getIdentifier());
            $scopes[$oauthScopeTransfer->getIdentifier()] = $scope;
        }

        return $scopes;
    }
}
