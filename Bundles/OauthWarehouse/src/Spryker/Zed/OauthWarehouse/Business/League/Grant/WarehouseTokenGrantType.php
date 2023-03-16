<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouse\Business\League\Grant;

use DateInterval;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;
use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeInterface;
use Spryker\Zed\OauthWarehouse\OauthWarehouseConfig;

class WarehouseTokenGrantType extends AbstractGrant implements GrantTypeInterface
{
    /**
     * @var string
     */
    protected const ID_WAREHOUSE = 'id_warehouse';

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_SCOPE = 'scope';

    /**
     * @var \Spryker\Zed\Oauth\Business\Model\League\Repositories\UserRepositoryInterface
     */
    protected $userRepository;

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
        $client = $this->validateClient($request);
        /** @var string $scopes */
        $scopes = $this->getRequestParameter(static::REQUEST_PARAMETER_SCOPE, $request, $this->defaultScope);
        $scopes = $this->validateScopes($scopes);
        $user = $this->validateUser($request, $client);

        $finalizedScopes = $this->scopeRepository->finalizeScopes($scopes, $this->getIdentifier(), $client, $user->getIdentifier());

        $accessToken = $this->issueAccessToken($this->getAccessTokenTTL(), $client, $user->getIdentifier(), $finalizedScopes);
        $refreshToken = $this->issueRefreshToken($accessToken);

        $responseType->setAccessToken($accessToken);
        $this->getEmitter()->emit($this->createRequestEvent(RequestEvent::ACCESS_TOKEN_ISSUED, $request));

        if ($refreshToken) {
            $responseType->setRefreshToken($refreshToken);
            $this->getEmitter()->emit($this->createRequestEvent(RequestEvent::REFRESH_TOKEN_ISSUED, $request));
        }

        return $responseType;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $clientEntity
     *
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     *
     * @return \League\OAuth2\Server\Entities\UserEntityInterface
     */
    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $clientEntity): UserEntityInterface
    {
        $idWarehouse = $this->getRequestParameter(static::ID_WAREHOUSE, $request);

        if ($idWarehouse === null) {
            throw OAuthServerException::invalidRequest(static::ID_WAREHOUSE);
        }

        $userEntity = $this->userRepository->getUserEntityByRequest(
            (array)$request->getParsedBody(),
            $this->getIdentifier(),
            $clientEntity,
        );

        if ($userEntity === null) {
            $this->getEmitter()->emit($this->createRequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));

            throw OAuthServerException::invalidCredentials();
        }

        return $userEntity;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return OauthWarehouseConfig::WAREHOUSE_GRANT_TYPE;
    }

    /**
     * @param string $requestEvent
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \League\OAuth2\Server\RequestEvent
     */
    protected function createRequestEvent(string $requestEvent, ServerRequestInterface $request): RequestEvent
    {
        return new RequestEvent($requestEvent, $request);
    }

    /**
     * @return \DateInterval
     */
    protected function getAccessTokenTTL(): DateInterval
    {
        return new DateInterval(OauthWarehouseConfig::WAREHOUSE_TOKEN_TTL);
    }
}
