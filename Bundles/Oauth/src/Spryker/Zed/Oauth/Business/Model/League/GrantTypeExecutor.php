<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League;

use DateInterval;
use Generated\Shared\Transfer\OauthErrorTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AbstractGrant;
use Spryker\Zed\Oauth\OauthConfig;

class GrantTypeExecutor implements GrantTypeExecutorInterface
{
    /**
     * @var \League\OAuth2\Server\AuthorizationServer
     */
    protected $authorizationServer;

    /**
     * @var \Spryker\Zed\Oauth\Business\Model\League\RepositoryBuilderInterface
     */
    protected $repositoryBuilder;

    /**
     * @var \Spryker\Zed\Oauth\OauthConfig
     */
    protected $oauthConfig;

    /**
     * @param \League\OAuth2\Server\AuthorizationServer $authorizationServer
     * @param \Spryker\Zed\Oauth\Business\Model\League\RepositoryBuilderInterface $repositoryBuilder
     * @param \Spryker\Zed\Oauth\OauthConfig $oauthConfig
     */
    public function __construct(
        AuthorizationServer $authorizationServer,
        RepositoryBuilderInterface $repositoryBuilder,
        OauthConfig $oauthConfig
    ) {
        $this->authorizationServer = $authorizationServer;
        $this->repositoryBuilder = $repositoryBuilder;
        $this->oauthConfig = $oauthConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     * @param \League\OAuth2\Server\Grant\AbstractGrant $grantType
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function processAccessTokenRequest(OauthRequestTransfer $oauthRequestTransfer, AbstractGrant $grantType): OauthResponseTransfer
    {
        $oauthResponseTransfer = (new OauthResponseTransfer())
            ->setIsValid(false);

        try {
            $accessTokenRequest = new ServerRequest('POST', '', []);
            $accessTokenRequest = $accessTokenRequest->withParsedBody($oauthRequestTransfer->toArray());

            $grantType->setUserRepository($this->repositoryBuilder->createUserRepository());
            $grantType->setRefreshTokenRepository($this->repositoryBuilder->createRefreshTokenRepository());
            $grantType->setRefreshTokenTTL(
                new DateInterval($this->oauthConfig->getRefreshTokenTTL())
            );

            $this->authorizationServer->enableGrantType(
                $grantType,
                new DateInterval($this->oauthConfig->getAccessTokenTTL())
            );

            $response = $this->authorizationServer->respondToAccessTokenRequest($accessTokenRequest, new Response());

            $data = (string)$response->getBody();

            return $oauthResponseTransfer
                ->fromArray(json_decode($data, true), true)
                ->setIsValid(true);
        } catch (OAuthServerException $exception) {
            $oauthErrorTransfer = new OauthErrorTransfer();
            $oauthErrorTransfer
                ->setErrorType($exception->getErrorType())
                ->setMessage($exception->getMessage());

            $oauthResponseTransfer->setError($oauthErrorTransfer);
        }

        return $oauthResponseTransfer;
    }
}
