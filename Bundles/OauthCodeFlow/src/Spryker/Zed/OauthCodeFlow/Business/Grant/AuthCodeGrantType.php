<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCodeFlow\Business\Grant;

use DateInterval;
use DateTimeImmutable;
use League\OAuth2\Server\CodeChallengeVerifiers\PlainVerifier;
use League\OAuth2\Server\CodeChallengeVerifiers\S256Verifier;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use League\OAuth2\Server\RequestAccessTokenEvent;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\RequestRefreshTokenEvent;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use LogicException;
use Psr\Http\Message\ServerRequestInterface;
use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeInterface;
use Spryker\Zed\Oauth\Business\Model\League\Repositories\RefreshTokenRepositoryInterface;
use Spryker\Zed\OauthCodeFlow\OauthCodeFlowConfig;
use stdClass;

class AuthCodeGrantType extends AuthCodeGrant implements GrantTypeInterface
{
    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_APPLICATION_NAME = 'request_application';

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_CODE = 'code';

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_REDIRECT_URI = 'redirect_uri';

    /**
     * @var \DateInterval
     */
    protected $authCodeTTL;

    /**
     * @var \Spryker\Zed\Oauth\Business\Model\League\Repositories\AccessTokenRepositoryInterface
     */
    protected $accessTokenRepository;

    /**
     * @var \Spryker\Zed\Oauth\Business\Model\League\Repositories\ScopeRepositoryInterface
     */
    protected $scopeRepository;

    /**
     * @var array<\League\OAuth2\Server\CodeChallengeVerifiers\CodeChallengeVerifierInterface>
     */
    protected $codeChallengeVerifiers = [];

    /**
     * @var string
     */
    protected $applicationContext;

    /**
     * @param \Spryker\Zed\OauthCodeFlow\OauthCodeFlowConfig $config
     * @param \League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface $authCodeRepository
     * @param \Spryker\Zed\Oauth\Business\Model\League\Repositories\RefreshTokenRepositoryInterface $refreshTokenRepository
     */
    public function __construct(
        OauthCodeFlowConfig $config,
        AuthCodeRepositoryInterface $authCodeRepository,
        RefreshTokenRepositoryInterface $refreshTokenRepository
    ) {
        $this->authCodeTTL = new DateInterval($config->getAuthCodeTTL());

        parent::__construct(
            $authCodeRepository,
            $refreshTokenRepository,
            $this->authCodeTTL,
        );

        $this->setEncryptionKey($config->getEncryptionKey());

        if (in_array('sha256', hash_algos(), true)) {
            $s256Verifier = new S256Verifier();
            $this->codeChallengeVerifiers[$s256Verifier->getMethod()] = $s256Verifier;
        }

        $plainVerifier = new PlainVerifier();
        $this->codeChallengeVerifiers[$plainVerifier->getMethod()] = $plainVerifier;
    }

    /**
     * Respond to an access token request.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \League\OAuth2\Server\ResponseTypes\ResponseTypeInterface $responseType
     * @param \DateInterval $accessTokenTTL
     *
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     *
     * @return \League\OAuth2\Server\ResponseTypes\ResponseTypeInterface
     */
    public function respondToAccessTokenRequest(
        ServerRequestInterface $request,
        ResponseTypeInterface $responseType,
        DateInterval $accessTokenTTL
    ): ResponseTypeInterface {
        [$clientId] = $this->getClientCredentials($request);

        $client = $this->getClientEntityOrFail($clientId, $request);

        // Only validate the client if it is confidential
        if ($client->isConfidential()) {
            $this->validateClient($request);
        }

        $encryptedAuthCode = $this->getRequestParameter(static::REQUEST_PARAMETER_CODE, $request, null);

        if (!is_string($encryptedAuthCode)) {
            throw OAuthServerException::invalidRequest(static::REQUEST_PARAMETER_CODE);
        }

        try {
            $authCodePayload = json_decode($this->decrypt($encryptedAuthCode));

            $this->validateAuthorizationCode($authCodePayload, $client, $request);
        } catch (LogicException $e) {
            throw OAuthServerException::invalidRequest(static::REQUEST_PARAMETER_CODE, 'Cannot decrypt the authorization code', $e);
        }

        $scopes = [];
        foreach ($authCodePayload->scopes as $scope) {
            $scopes[] = $this->scopeRepository->createScopeEntity($scope);
        }

        $this->validateCodeVerifier($request, $authCodePayload);

        // Issue and persist new access token
        $accessToken = $this->issueAccessToken($accessTokenTTL, $client, $authCodePayload->user_id, $scopes);
        $this->getEmitter()->emit(new RequestAccessTokenEvent(RequestEvent::ACCESS_TOKEN_ISSUED, $request, $accessToken));
        $responseType->setAccessToken($accessToken);

        // Issue and persist new refresh token if given
        $refreshToken = $this->issueRefreshToken($accessToken);

        if ($refreshToken !== null) {
            $this->getEmitter()->emit(new RequestRefreshTokenEvent(RequestEvent::REFRESH_TOKEN_ISSUED, $request, $refreshToken));
            $responseType->setRefreshToken($refreshToken);
        }

        // Revoke used auth code
        $this->authCodeRepository->revokeAuthCode($authCodePayload->auth_code_id);

        return $responseType;
    }

    /**
     * Validate the authorization code.
     *
     * @param \stdClass $authCodePayload
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $client
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     *
     * @return void
     */
    protected function validateAuthorizationCode(
        stdClass $authCodePayload,
        ClientEntityInterface $client,
        ServerRequestInterface $request
    ): void {
        $requestApplicationContext = $this->getRequestParameter(static::REQUEST_PARAMETER_APPLICATION_NAME, $request);
        if ($requestApplicationContext !== $authCodePayload->applicationContext) {
            throw OAuthServerException::invalidRequest(static::REQUEST_PARAMETER_CODE, 'Authorization code was not issued to this application');
        }

        if (!property_exists($authCodePayload, 'auth_code_id')) {
            throw OAuthServerException::invalidRequest(static::REQUEST_PARAMETER_CODE, 'Authorization code malformed');
        }

        if (time() > $authCodePayload->expire_time) {
            throw OAuthServerException::invalidRequest(static::REQUEST_PARAMETER_CODE, 'Authorization code has expired');
        }

        if ($this->authCodeRepository->isAuthCodeRevoked($authCodePayload->auth_code_id) === true) {
            throw OAuthServerException::invalidRequest(static::REQUEST_PARAMETER_CODE, 'Authorization code has been revoked');
        }

        if ($authCodePayload->client_id !== $client->getIdentifier()) {
            throw OAuthServerException::invalidRequest(static::REQUEST_PARAMETER_CODE, 'Authorization code was not issued to this client');
        }

        // The redirect URI is required in this request
        $redirectUri = $this->getRequestParameter(static::REQUEST_PARAMETER_REDIRECT_URI, $request, null);
        if (empty($authCodePayload->redirect_uri) === false && $redirectUri === null) {
            throw OAuthServerException::invalidRequest(static::REQUEST_PARAMETER_REDIRECT_URI);
        }

        if ($authCodePayload->redirect_uri !== $redirectUri) {
            throw OAuthServerException::invalidRequest(static::REQUEST_PARAMETER_REDIRECT_URI, 'Invalid redirect URI');
        }
    }

    /**
     * @param \DateInterval $accessTokenTTL
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $client
     * @param string|null $userIdentifier
     * @param array<\League\OAuth2\Server\Entities\ScopeEntityInterface> $scopes
     *
     * @return \League\OAuth2\Server\Entities\AccessTokenEntityInterface
     */
    protected function issueAccessToken(
        DateInterval $accessTokenTTL,
        ClientEntityInterface $client,
        $userIdentifier,
        array $scopes = []
    ) {
        $accessTokenTransfer = $this->accessTokenRepository->findAccessToken($client, $scopes);
        if ($accessTokenTransfer !== null) {
            $accessToken = $this->accessTokenRepository->getNewToken($client, $scopes, $userIdentifier);
            /** @phpstan-var \DateTime $expirityDate */
            $expirityDate = $accessTokenTransfer->getExpiresAtOrFail();
            $accessToken->setExpiryDateTime(DateTimeImmutable::createFromMutable($expirityDate));
            $accessToken->setPrivateKey($this->privateKey);
            $accessToken->setIdentifier($accessTokenTransfer->getAccessTokenIdentifierOrFail());

            return $accessToken;
        }

        return parent::issueAccessToken(
            $accessTokenTTL,
            $client,
            $userIdentifier,
            $scopes,
        );
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \stdClass $authCodePayload
     *
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     *
     * @return void
     */
    protected function validateCodeVerifier(
        ServerRequestInterface $request,
        stdClass $authCodePayload
    ): void {
        if (!empty($authCodePayload->code_challenge)) {
            $codeVerifier = $this->getRequestParameter('code_verifier', $request, null);

            if ($codeVerifier === null) {
                throw OAuthServerException::invalidRequest('code_verifier');
            }

            // Validate code_verifier according to RFC-7636
            // @see: https://tools.ietf.org/html/rfc7636#section-4.1
            if (preg_match('/^[A-Za-z0-9-._~]{43,128}$/', $codeVerifier) !== 1) {
                throw OAuthServerException::invalidRequest(
                    'code_verifier',
                    'Code Verifier must follow the specifications of RFC-7636.',
                );
            }

            if (property_exists($authCodePayload, 'code_challenge_method')) {
                if (isset($this->codeChallengeVerifiers[$authCodePayload->code_challenge_method])) {
                    $codeChallengeVerifier = $this->codeChallengeVerifiers[$authCodePayload->code_challenge_method];

                    if ($codeChallengeVerifier->verifyCodeChallenge($codeVerifier, $authCodePayload->code_challenge) === false) {
                        throw OAuthServerException::invalidGrant('Failed to verify `code_verifier`.');
                    }
                } else {
                    throw OAuthServerException::serverError(
                        sprintf(
                            'Unsupported code challenge method `%s`',
                            $authCodePayload->code_challenge_method,
                        ),
                    );
                }
            }
        }
    }
}
