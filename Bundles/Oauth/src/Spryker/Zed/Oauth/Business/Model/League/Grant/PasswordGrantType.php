<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Grant;

use DateInterval;
use DateTimeImmutable;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;
use Spryker\Zed\Oauth\OauthConfig;

class PasswordGrantType extends AbstractGrant implements GrantTypeInterface
{
    /**
     * @var \Spryker\Zed\Oauth\Business\Model\League\Repositories\ScopeRepositoryInterface
     */
    protected $scopeRepository;

    /**
     * @var \Spryker\Zed\Oauth\Business\Model\League\Repositories\AccessTokenRepositoryInterface
     */
    protected $accessTokenRepository;

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_PASSWORD = 'password';

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_SCOPE = 'scope';

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_USERNAME = 'username';

    /**
     * @var string
     */
    protected const REQUEST_APPLICATION_NAME = 'request_application';

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
        $applicationName = $this->getRequestParameter(static::REQUEST_APPLICATION_NAME, $request);
        $client = $this->validateClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter(static::REQUEST_PARAMETER_SCOPE, $request, $this->defaultScope), null, $applicationName);
        $user = $this->validateUser($request, $client);

        // Finalize the requested scopes
        $finalizedScopes = $this->scopeRepository->finalizeScopes($scopes, $this->getIdentifier(), $client, $user->getIdentifier(), $applicationName);

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
     * @param array|string $scopes
     * @param string|null $redirectUri
     * @param string|null $applicationName
     *
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     *
     * @return array<\League\OAuth2\Server\Entities\ScopeEntityInterface>
     */
    public function validateScopes($scopes, $redirectUri = null, ?string $applicationName = null): array
    {
        if (is_string($scopes)) {
            $scopes = $this->convertScopesQueryStringToArray($scopes);
        }

        if (!is_array($scopes)) {
            throw OAuthServerException::invalidRequest('scope');
        }

        $validScopes = [];

        foreach ($scopes as $scopeItem) {
            $scope = $this->scopeRepository->getScopeEntityByIdentifier($scopeItem, $applicationName);

            if ($scope instanceof ScopeEntityInterface) {
                $validScopes[] = $scope;
            }
        }

        return $validScopes;
    }

    /**
     * @param string $scopes
     *
     * @return array<string>
     */
    protected function convertScopesQueryStringToArray(string $scopes): array
    {
        return array_filter(explode(static::SCOPE_DELIMITER_STRING, trim($scopes)), function ($scope) {
            return $scope !== '';
        });
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $client
     *
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
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
            $client,
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

    /**
     * Issue an access token.
     *
     * @param \DateInterval $accessTokenTTL
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $client
     * @param string|null $userIdentifier
     * @param array<\League\OAuth2\Server\Entities\ScopeEntityInterface> $scopes
     *
     * @throws \League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException
     *
     * @return \League\OAuth2\Server\Entities\AccessTokenEntityInterface
     */
    protected function issueAccessToken(
        DateInterval $accessTokenTTL,
        ClientEntityInterface $client,
        $userIdentifier,
        array $scopes = []
    ): AccessTokenEntityInterface {
        $maxGenerationAttempts = static::MAX_RANDOM_TOKEN_GENERATION_ATTEMPTS;

        $accessToken = $this->accessTokenRepository->getNewToken($client, $scopes, $userIdentifier);
        $accessToken->setExpiryDateTime((new DateTimeImmutable())->add($accessTokenTTL));
        $accessToken->setPrivateKey($this->privateKey);

        $accessTokenTransfer = $this->accessTokenRepository->findAccessToken($client, $scopes);
        if ($accessTokenTransfer !== null) {
            /** @phpstan-var \DateTime $expirityDate */
            $expirityDate = $accessTokenTransfer->getExpiresAtOrFail();
            $accessToken->setExpiryDateTime(DateTimeImmutable::createFromMutable($expirityDate));

            $accessToken->setIdentifier($accessTokenTransfer->getAccessTokenIdentifierOrFail());

            return $accessToken;
        }

        while ($maxGenerationAttempts-- > 0) {
            $accessToken->setIdentifier($this->generateUniqueIdentifier());
            try {
                $this->accessTokenRepository->persistNewAccessToken($accessToken);

                break;
            } catch (UniqueTokenIdentifierConstraintViolationException $e) {
                if ($maxGenerationAttempts === 0) {
                    throw $e;
                }
            }
        }

        return $accessToken;
    }
}
