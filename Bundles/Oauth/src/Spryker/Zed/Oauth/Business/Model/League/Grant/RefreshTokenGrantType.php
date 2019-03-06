<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Grant;

use DateInterval;
use Exception;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;
use Spryker\Zed\Oauth\OauthConfig;

class RefreshTokenGrantType extends AbstractGrant implements GrantTypeInterface
{
    protected const KEY_ACCESS_TOKEN_ID = 'access_token_id';
    protected const KEY_CLIENT_ID = 'client_id';
    protected const KEY_EXPIRE_TIME = 'expire_time';
    protected const KEY_REFRESH_TOKEN_ID = 'refresh_token_id';
    protected const KEY_SCOPES = 'scopes';
    protected const KEY_USER_ID = 'user_id';
    protected const REQUEST_PARAMETER_REFRESH_TOKEN = 'refresh_token';
    protected const REQUEST_PARAMETER_SCOPE = 'scope';

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
        $oldRefreshToken = $this->validateOldRefreshToken($request, $client->getIdentifier());
        $scopes = $this->validateScopes($this->getRequestParameter(
            static::REQUEST_PARAMETER_SCOPE,
            $request,
            implode(self::SCOPE_DELIMITER_STRING, $oldRefreshToken[static::KEY_SCOPES])
        ));

        // The OAuth spec says that a refreshed access token can have the original scopes or fewer so ensure
        // the request doesn't include any new scopes
        foreach ($scopes as $scope) {
            if (in_array($scope->getIdentifier(), $oldRefreshToken[static::KEY_SCOPES], true) === false) {
                throw OAuthServerException::invalidScope($scope->getIdentifier());
            }
        }

        // Expire old tokens
        $this->accessTokenRepository->revokeAccessToken($oldRefreshToken[static::KEY_ACCESS_TOKEN_ID]);
        $this->refreshTokenRepository->revokeRefreshToken($oldRefreshToken[static::KEY_REFRESH_TOKEN_ID]);

        // Issue and persist new tokens
        $accessToken = $this->issueAccessToken($accessTokenTTL, $client, $oldRefreshToken[static::KEY_USER_ID], $scopes);
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
        return OauthConfig::GRANT_TYPE_REFRESH_TOKEN;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param string $clientId
     *
     * @return array
     */
    protected function validateOldRefreshToken(ServerRequestInterface $request, $clientId)
    {
        $encryptedRefreshToken = $this->getRequestParameter(static::REQUEST_PARAMETER_REFRESH_TOKEN, $request);
        if ($encryptedRefreshToken === null) {
            throw OAuthServerException::invalidRequest(static::REQUEST_PARAMETER_REFRESH_TOKEN);
        }

        // Validate refresh token
        try {
            $refreshToken = $this->decrypt($encryptedRefreshToken);
        } catch (Exception $e) {
            throw OAuthServerException::invalidRefreshToken('Cannot decrypt the refresh token', $e);
        }

        $refreshTokenData = json_decode($refreshToken, true);
        if ($refreshTokenData[static::KEY_CLIENT_ID] !== $clientId) {
            $this->getEmitter()->emit($this->createRequestEvent(RequestEvent::REFRESH_TOKEN_CLIENT_FAILED, $request));
            throw OAuthServerException::invalidRefreshToken('Token is not linked to client');
        }

        if ($refreshTokenData[static::KEY_EXPIRE_TIME] < time()) {
            throw OAuthServerException::invalidRefreshToken('Token has expired');
        }

        if ($this->refreshTokenRepository->isRefreshTokenRevoked($refreshTokenData[self::KEY_REFRESH_TOKEN_ID]) === true) {
            throw OAuthServerException::invalidRefreshToken('Token has been revoked');
        }

        return $refreshTokenData;
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
