<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Grant;

use DateInterval;
use Generated\Shared\Transfer\OauthErrorTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Spryker\Zed\Oauth\OauthConfig;

class GrantTypeExecutor implements GrantTypeExecutorInterface
{
    /**
     * @var \League\OAuth2\Server\AuthorizationServer
     */
    protected $authorizationServer;

    /**
     * @var \Spryker\Zed\Oauth\OauthConfig
     */
    protected $oauthConfig;

    /**
     * @param \League\OAuth2\Server\AuthorizationServer $authorizationServer
     * @param \Spryker\Zed\Oauth\OauthConfig $oauthConfig
     */
    public function __construct(
        AuthorizationServer $authorizationServer,
        OauthConfig $oauthConfig
    ) {
        $this->authorizationServer = $authorizationServer;
        $this->oauthConfig = $oauthConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     * @param \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeInterface $grant
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function processAccessTokenRequest(OauthRequestTransfer $oauthRequestTransfer, GrantTypeInterface $grant): OauthResponseTransfer
    {
        try {
            $accessTokenRequest = $this->createAccessTokenRequest($oauthRequestTransfer);

            $this->authorizationServer->enableGrantType(
                $grant,
                new DateInterval($this->oauthConfig->getAccessTokenTTL())
            );

            $response = $this->authorizationServer->respondToAccessTokenRequest($accessTokenRequest, new Response());

            return $this->createOauthResponseTransfer($response);
        } catch (OAuthServerException $exception) {
            return $this->createErrorOauthResponseTransfer($exception);
        }
    }

    /**
     * @param \League\OAuth2\Server\Exception\OAuthServerException $exception
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    protected function createErrorOauthResponseTransfer(OAuthServerException $exception): OauthResponseTransfer
    {
        $oauthErrorTransfer = new OauthErrorTransfer();
        $oauthErrorTransfer
            ->setErrorType($exception->getErrorType())
            ->setMessage($exception->getMessage());

        return (new OauthResponseTransfer())
            ->setIsValid(false)
            ->setError($oauthErrorTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \GuzzleHttp\Psr7\ServerRequest
     */
    protected function createAccessTokenRequest(OauthRequestTransfer $oauthRequestTransfer): ServerRequest
    {
        $accessTokenRequest = new ServerRequest('POST', '', []);
        $accessTokenRequest = $accessTokenRequest->withParsedBody($oauthRequestTransfer->toArray());

        return $accessTokenRequest;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    protected function createOauthResponseTransfer(ResponseInterface $response): OauthResponseTransfer
    {
        $data = (string)$response->getBody();

        return (new OauthResponseTransfer())
            ->fromArray(json_decode($data, true), true)
            ->setIsValid(true);
    }
}
