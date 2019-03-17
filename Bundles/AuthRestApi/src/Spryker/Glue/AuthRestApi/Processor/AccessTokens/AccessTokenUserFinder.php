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

class AccessTokenUserFinder implements AccessTokenUserFinderInterface
{
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
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestUserTransfer|null
     */
    public function findUser(RestRequestInterface $restRequest): ?RestUserTransfer
    {
        $authorizationToken = $restRequest->getHttpRequest()->headers->get(AuthRestApiConfig::HEADER_AUTHORIZATION);

        if (!$authorizationToken) {
            return null;
        }

        $authAccessTokenValidationResponseTransfer = $this->findUserByAccessToken((string)$authorizationToken);

        return $this->findRestUserTransfer($restRequest, $authAccessTokenValidationResponseTransfer);
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
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer $oauthAccessTokenValidationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestUserTransfer|null
     */
    protected function findRestUserTransfer(
        RestRequestInterface $restRequest,
        OauthAccessTokenValidationResponseTransfer $oauthAccessTokenValidationResponseTransfer
    ): ?RestUserTransfer {
        if (!$oauthAccessTokenValidationResponseTransfer->getIsValid()) {
            return null;
        }

        $customerIdentifier = json_decode($oauthAccessTokenValidationResponseTransfer->getOauthUserId(), true);

        return $this->mapRestUserTransfer($customerIdentifier, $restRequest);
    }

    /**
     * @param array $customerIdentifier
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestUserTransfer
     */
    protected function mapRestUserTransfer(array $customerIdentifier, RestRequestInterface $restRequest): RestUserTransfer
    {
        $restUserTransfer = (new RestUserTransfer())
            ->fromArray($customerIdentifier, true);

        foreach ($this->restUserExpanderPlugins as $restUserExpanderPlugin) {
            $restUserTransfer = $restUserExpanderPlugin->expand($restUserTransfer, $restRequest);
        }

        return $restUserTransfer;
    }
}
