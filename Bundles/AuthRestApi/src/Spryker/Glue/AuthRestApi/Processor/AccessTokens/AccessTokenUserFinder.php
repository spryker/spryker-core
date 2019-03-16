<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Processor\AccessTokens;

use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestUserIdentifierTransfer;
use Spryker\Glue\AuthRestApi\AuthRestApiConfig;
use Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\User;
use Spryker\Glue\GlueApplication\Rest\Request\Data\UserInterface;

class AccessTokenUserFinder implements AccessTokenUserFinderInterface
{
    /**
     * @var \Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface
     */
    protected $oauthClient;

    /**
     * @var \Spryker\Glue\AuthRestApiExtension\Dependency\Plugin\RestUserIdentifierExpanderPluginInterface[]
     */
    protected $restUserIdentifierExpanderPlugins;

    /**
     * @param \Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface $oauthClient
     * @param \Spryker\Glue\AuthRestApiExtension\Dependency\Plugin\RestUserIdentifierExpanderPluginInterface[] $restUserIdentifierExpanderPlugins
     */
    public function __construct(
        AuthRestApiToOauthClientInterface $oauthClient,
        array $restUserIdentifierExpanderPlugins
    ) {
        $this->oauthClient = $oauthClient;
        $this->restUserIdentifierExpanderPlugins = $restUserIdentifierExpanderPlugins;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\UserInterface|null
     */
    public function findUser(RestRequestInterface $restRequest): ?UserInterface
    {
        $authorizationToken = $restRequest->getHttpRequest()->headers->get(AuthRestApiConfig::HEADER_AUTHORIZATION);

        if (!$authorizationToken) {
            return null;
        }

        $authAccessTokenValidationResponseTransfer = $this->findUserByAccessToken((string)$authorizationToken);

        return $this->getUser($restRequest, $authAccessTokenValidationResponseTransfer);
    }

    /**
     * @param string $authorizationToken
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer
     */
    protected function findUserByAccessToken(string $authorizationToken): OauthAccessTokenValidationResponseTransfer
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
     * @param string $authorizationToken
     *
     * @return array
     */
    protected function extractToken(string $authorizationToken): array
    {
        return preg_split('/\s+/', $authorizationToken);
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
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer $authAccessTokenValidationResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\UserInterface
     */
    protected function getUser(
        RestRequestInterface $restRequest,
        OauthAccessTokenValidationResponseTransfer $authAccessTokenValidationResponseTransfer
    ): UserInterface {
        $customerIdentifier = json_decode($authAccessTokenValidationResponseTransfer->getOauthUserId(), true);
        $restUserIdentifierTransfer = $this->getRestUserIdentifierTransfer($customerIdentifier, $restRequest);

        return new User(
            $customerIdentifier['id_customer'],
            $customerIdentifier['customer_reference'],
            $authAccessTokenValidationResponseTransfer->getOauthScopes(),
            $restUserIdentifierTransfer
        );
    }

    /**
     * @param array $customerIdentifier
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestUserIdentifierTransfer
     */
    protected function getRestUserIdentifierTransfer(array $customerIdentifier, RestRequestInterface $restRequest): RestUserIdentifierTransfer
    {
        $restUserIdentifierTransfer = (new RestUserIdentifierTransfer())
            ->fromArray($customerIdentifier, true);

        foreach ($this->restUserIdentifierExpanderPlugins as $restUserIdentifierExpanderPlugin) {
            $restUserIdentifierTransfer = $restUserIdentifierExpanderPlugin->expand($restUserIdentifierTransfer, $restRequest);
        }

        return $restUserIdentifierTransfer;
    }
}
