<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Processor\AccessTokens;

use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\AuthRestApi\AuthRestApiConfig;
use Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessTokenValidator implements AccessTokenValidatorInterface
{
    protected const REQUEST_ATTRIBUTE_IS_PROTECTED = 'is-protected';
    protected const HEADER_AUTHORIZATION = 'Authorization';

    /**
     * @var \Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface
     */
    protected $oauthClient;

    /**
     * @var \Spryker\Glue\AuthRestApiExtension\Dependency\Plugin\RestUserExpanderPluginInterface[]
     */
    protected $restUserExpanderPlugins;

    /**
     * @param \Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface $oauthClient
     * @param \Spryker\Glue\AuthRestApiExtension\Dependency\Plugin\RestUserExpanderPluginInterface[] $restUserExpanderPlugins
     */
    public function __construct(
        AuthRestApiToOauthClientInterface $oauthClient,
        array $restUserExpanderPlugins
    ) {
        $this->oauthClient = $oauthClient;
        $this->restUserExpanderPlugins = $restUserExpanderPlugins;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(Request $request, RestRequestInterface $restRequest): ?RestErrorMessageTransfer
    {
        $isProtected = $request->attributes->get(static::REQUEST_ATTRIBUTE_IS_PROTECTED, false);

        $authorizationToken = $request->headers->get(static::HEADER_AUTHORIZATION);
        if (!$authorizationToken && $isProtected === true) {
            return $this->createErrorMessageTransfer(
                AuthRestApiConfig::RESPONSE_DETAIL_MISSING_ACCESS_TOKEN,
                Response::HTTP_FORBIDDEN,
                AuthRestApiConfig::RESPONSE_CODE_FORBIDDEN
            );
        }

        if (!$authorizationToken) {
            return null;
        }

        $authAccessTokenValidationResponseTransfer = $this->validateAccessToken((string)$authorizationToken);

        if (!$authAccessTokenValidationResponseTransfer->getIsValid()) {
            return $this->createErrorMessageTransfer(
                AuthRestApiConfig::RESPONSE_DETAIL_INVALID_ACCESS_TOKEN,
                Response::HTTP_UNAUTHORIZED,
                AuthRestApiConfig::RESPONSE_CODE_ACCESS_CODE_INVALID
            );
        }
        $this->setRestUserData($restRequest, $authAccessTokenValidationResponseTransfer);

        return null;
    }

    /**
     * @param string $detail
     * @param int $status
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createErrorMessageTransfer(
        string $detail,
        int $status,
        string $code
    ): RestErrorMessageTransfer {

        return (new RestErrorMessageTransfer())
            ->setDetail($detail)
            ->setStatus($status)
            ->setCode($code);
    }

    /**
     * @param string $authorizationToken
     *
     * @return array
     */
    protected function extractToken(string $authorizationToken): array
    {
        return preg_split('/\s+/', $authorizationToken);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer $authAccessTokenValidationResponseTransfer
     *
     * @return void
     */
    protected function setRestUserData(
        RestRequestInterface $restRequest,
        OauthAccessTokenValidationResponseTransfer $authAccessTokenValidationResponseTransfer
    ): void {

        $userIdentifier = json_decode($authAccessTokenValidationResponseTransfer->getOauthUserId(), true);
        $restUserTransfer = $this->getRestUserTransfer($userIdentifier, $restRequest);

        $restRequest->setUser(
            $userIdentifier['id_customer'],
            $userIdentifier['customer_reference'],
            $authAccessTokenValidationResponseTransfer->getOauthScopes()
        );

        $restRequest->setRestUser($restUserTransfer);
    }

    /**
     * @param string $authorizationToken
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer
     */
    protected function validateAccessToken(string $authorizationToken): OauthAccessTokenValidationResponseTransfer
    {
        [$type, $accessToken] = $this->extractToken($authorizationToken);

        $authAccessTokenValidationRequestTransfer = new OauthAccessTokenValidationRequestTransfer();
        $authAccessTokenValidationRequestTransfer
            ->setAccessToken($accessToken)
            ->setType($type);

        $authAccessTokenValidationResponseTransfer = $this->oauthClient->validateAccessToken(
            $authAccessTokenValidationRequestTransfer
        );

        return $authAccessTokenValidationResponseTransfer;
    }

    /**
     * @param array $userIdentifier
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestUserTransfer
     */
    protected function getRestUserTransfer(array $userIdentifier, RestRequestInterface $restRequest): RestUserTransfer
    {
        $restUserTransfer = (new RestUserTransfer())
            ->fromArray($userIdentifier, true)
            ->setNaturalIdentifier($userIdentifier['customer_reference'])
            ->setSurrogateIdentifier($userIdentifier['id_customer']);

        return $this->executeRestUserExpanderPlugins($restRequest, $restUserTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestUserTransfer $restUserTransfer
     *
     * @return \Generated\Shared\Transfer\RestUserTransfer
     */
    protected function executeRestUserExpanderPlugins(RestRequestInterface $restRequest, RestUserTransfer $restUserTransfer): RestUserTransfer
    {
        foreach ($this->restUserExpanderPlugins as $restUserExpanderPlugin) {
            $restUserTransfer = $restUserExpanderPlugin->expand($restUserTransfer, $restRequest);
        }

        return $restUserTransfer;
    }
}
