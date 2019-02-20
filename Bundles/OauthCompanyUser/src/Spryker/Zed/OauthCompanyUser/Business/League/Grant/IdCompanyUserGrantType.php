<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser\Business\League\Grant;

use DateInterval;
use Generated\Shared\Transfer\OauthUserTransfer;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;
use Spryker\Zed\Oauth\Business\Model\League\Entities\UserEntity;
use Spryker\Zed\OauthCompanyUser\Business\CompanyUser\CompanyUserProviderInterface;

class IdCompanyUserGrantType extends AbstractGrant
{
    /**
     * @var \Spryker\Zed\OauthCompanyUser\Business\CompanyUser\CompanyUserProviderInterface
     */
    protected $companyUserProvider;

    /**
     * @param \Spryker\Zed\OauthCompanyUser\Business\CompanyUser\CompanyUserProviderInterface $companyUserProvider
     */
    public function __construct(CompanyUserProviderInterface $companyUserProvider)
    {
        $this->companyUserProvider = $companyUserProvider;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \League\OAuth2\Server\ResponseTypes\ResponseTypeInterface $responseType
     * @param \DateInterval $accessTokenTTL
     *
     * @return \League\OAuth2\Server\ResponseTypes\ResponseTypeInterface
     */
    public function respondToAccessTokenRequest(
        ServerRequestInterface $request,
        ResponseTypeInterface $responseType,
        DateInterval $accessTokenTTL
    ): ResponseTypeInterface {
        // Validate request
        $client = $this->validateClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request, $this->defaultScope));
        $user = $this->validateUser($request, $client);

        // Finalize the requested scopes
        $finalizedScopes = $this->scopeRepository->finalizeScopes($scopes, $this->getIdentifier(), $client, $user->getIdentifier());

        // Issue and persist new tokens
        $accessToken = $this->issueAccessToken($accessTokenTTL, $client, $user->getIdentifier(), $finalizedScopes);
        $refreshToken = $this->issueRefreshToken($accessToken);

        // Send events to emitter
        $this->getEmitter()->emit(new RequestEvent(RequestEvent::ACCESS_TOKEN_ISSUED, $request));
        $this->getEmitter()->emit(new RequestEvent(RequestEvent::REFRESH_TOKEN_ISSUED, $request));

        // Inject tokens into response
        $responseType->setAccessToken($accessToken);
        $responseType->setRefreshToken($refreshToken);

        return $responseType;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $clientEntity
     *
     * @return \League\OAuth2\Server\Entities\UserEntityInterface
     */
    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $clientEntity)
    {
        $oauthUserTransfer = new OauthUserTransfer();
        $oauthUserTransfer->fromArray($request->getParsedBody(), true);
        $oauthUserTransfer->setClientId($clientEntity->getIdentifier())
            ->setGrantType($this->getIdentifier())
            ->setClientName($clientEntity->getName());
        $oauthUserTransfer = $this->companyUserProvider->getOauthCompanyUser($oauthUserTransfer);

        if ($oauthUserTransfer && $oauthUserTransfer->getIsSuccess() && $oauthUserTransfer->getUserIdentifier()) {
            return new UserEntity($oauthUserTransfer->getUserIdentifier());
        }

        $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));
        throw OAuthServerException::invalidCredentials();
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return 'idCompanyUser';
    }
}
