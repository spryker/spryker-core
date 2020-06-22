<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCryptography\Communication\Plugin\Oauth;

use BadMethodCallException;
use InvalidArgumentException;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\ValidationData;
use League\OAuth2\Server\CryptTrait;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\OauthExtention\Dependency\Plugin\AuthorizationValidatorPluginInterface;

// Todo: Move to behind the public API.
class BearerTokenAuthorizationValidatorPlugin extends AbstractPlugin implements AuthorizationValidatorPluginInterface
{
    use CryptTrait;

    /**
     * @var \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface
     */
    protected $accessTokenRepository;

    /**
     * @var array
     */
    protected $publicKeys;

    /**
     * @param \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface $repository
     *
     * @return void
     */
    public function setRepository(AccessTokenRepositoryInterface $repository): void
    {
        $this->accessTokenRepository = $repository;
    }

    /**
     * @param \League\OAuth2\Server\CryptKey[] $keys
     *
     * @return void
     */
    public function setPublicKeys(array $keys): void
    {
        $this->publicKeys = $keys;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function validateAuthorization(ServerRequestInterface $request)
    {
        if ($request->hasHeader('authorization') === false) {
            throw OAuthServerException::accessDenied('Missing "Authorization" header');
        }

        $header = $request->getHeader('authorization');
        $jwt = trim(preg_replace('/^(?:\s+)?Bearer\s/', '', $header[0]));

        try {
            // Attempt to parse and validate the JWT
            $token = (new Parser())->parse($jwt);
            try {
                $isTokenVerified = false;
                foreach ($this->publicKeys as $publicKey) {
                    if ($token->verify(new Sha256(), $publicKey->getKeyPath())) {
                        $isTokenVerified = true;
                    }
                }

                if (!$isTokenVerified) {
                    throw OAuthServerException::accessDenied('Access token could not be verified');
                }
            } catch (BadMethodCallException $exception) {
                throw OAuthServerException::accessDenied('Access token is not signed', null, $exception);
            }

            // Ensure access token hasn't expired
            $data = new ValidationData();
            $data->setCurrentTime(time());

            if ($token->validate($data) === false) {
                throw OAuthServerException::accessDenied('Access token is invalid');
            }

            // Check if token has been revoked
            if ($this->accessTokenRepository->isAccessTokenRevoked($token->getClaim('jti'))) {
                throw OAuthServerException::accessDenied('Access token has been revoked');
            }

            // Return the request with additional attributes
            return $request
                ->withAttribute('oauth_access_token_id', $token->getClaim('jti'))
                ->withAttribute('oauth_client_id', $token->getClaim('aud'))
                ->withAttribute('oauth_user_id', $token->getClaim('sub'))
                ->withAttribute('oauth_scopes', $token->getClaim('scopes'));
        } catch (InvalidArgumentException $exception) {
            // JWT couldn't be parsed so return the request as is
            throw OAuthServerException::accessDenied($exception->getMessage(), null, $exception);
        } catch (RuntimeException $exception) {
            //JWR couldn't be parsed so return the request as is
            throw OAuthServerException::accessDenied('Error while decoding to JSON', null, $exception);
        }
    }
}
