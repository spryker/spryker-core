<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Grant;

use DateInterval;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;
use Spryker\Zed\Oauth\OauthConfig;

class PasswordGrantType extends AbstractGrant implements GrantTypeInterface
{
    protected const REQUEST_PARAMETER_PASSWORD = 'password';
    protected const REQUEST_PARAMETER_SCOPE = 'scope';
    protected const REQUEST_PARAMETER_USERNAME = 'username';

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
    ) {
        // Validate request
        $client = $this->validateClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter(static::REQUEST_PARAMETER_SCOPE, $request, $this->defaultScope));
        $user = $this->validateUser($request, $client);

        // Finalize the requested scopes
        $finalizedScopes = $this->scopeRepository->finalizeScopes($scopes, $this->getIdentifier(), $client, $user->getIdentifier());

        // Issue and persist new tokens
        $accessToken = $this->issueAccessToken($accessTokenTTL, $client, $user->getIdentifier(), $finalizedScopes);
        $refreshToken = $this->issueRefreshToken($accessToken);

        // Send events to emitter
        $this->getEmitter()->emit($this->createRequestEvent(RequestEvent::ACCESS_TOKEN_ISSUED, $request));
        $this->getEmitter()->emit($this->createRequestEvent(RequestEvent::REFRESH_TOKEN_ISSUED, $request));

        // Inject tokens into response
        $responseType->setAccessToken($accessToken);
        $responseType->setRefreshToken($refreshToken);

        return $responseType;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return OauthConfig::GRANT_TYPE_PASSWORD;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $client
     *
     * @return \League\OAuth2\Server\Entities\UserEntityInterface
     */
    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client)
    {
        $username = $this->getRequestParameter(static::REQUEST_PARAMETER_USERNAME, $request);
        if ($username === null) {
            throw OAuthServerException::invalidRequest(static::REQUEST_PARAMETER_USERNAME);
        }

        $password = $this->getRequestParameter(static::REQUEST_PARAMETER_PASSWORD, $request);
        if ($password === null) {
            throw OAuthServerException::invalidRequest(static::REQUEST_PARAMETER_PASSWORD);
        }

        $user = $this->userRepository->getUserEntityByUserCredentials(
            $username,
            $password,
            $this->getIdentifier(),
            $client
        );
        if (!($user instanceof UserEntityInterface)) {
            $this->getEmitter()->emit($this->createRequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));

            throw OAuthServerException::invalidCredentials();
        }

        return $user;
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
}
