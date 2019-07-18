<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Processor\AccessTokens;

use Generated\Shared\Transfer\OauthAccessTokenDataTransfer;
use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\AuthRestApi\AuthRestApiConfig;
use Spryker\Glue\AuthRestApi\Dependency\Service\AuthRestApiToOauthServiceInterface;
use Spryker\Glue\AuthRestApi\Dependency\Service\AuthRestApiToUtilEncodingServiceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class AccessTokenUserFinder implements AccessTokenUserFinderInterface
{
    protected const KEY_CUSTOMER_REFERENCE = 'customer_reference';
    protected const KEY_ID_CUSTOMER = 'id_customer';

    /**
     * @var \Spryker\Glue\AuthRestApi\Dependency\Service\AuthRestApiToOauthServiceInterface
     */
    protected $oauthService;

    /**
     * @var \Spryker\Glue\AuthRestApi\Dependency\Service\AuthRestApiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Glue\AuthRestApiExtension\Dependency\Plugin\RestUserMapperPluginInterface[]
     */
    protected $restUserMapperPlugins;

    /**
     * @param \Spryker\Glue\AuthRestApi\Dependency\Service\AuthRestApiToOauthServiceInterface $oauthService
     * @param \Spryker\Glue\AuthRestApi\Dependency\Service\AuthRestApiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Glue\AuthRestApiExtension\Dependency\Plugin\RestUserMapperPluginInterface[] $restUserExpanderPlugins
     */
    public function __construct(
        AuthRestApiToOauthServiceInterface $oauthService,
        AuthRestApiToUtilEncodingServiceInterface $utilEncodingService,
        array $restUserExpanderPlugins
    ) {
        $this->oauthService = $oauthService;
        $this->utilEncodingService = $utilEncodingService;
        $this->restUserMapperPlugins = $restUserExpanderPlugins;
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

        $oauthAccessTokenDataTransfer = $this->findUserByAccessToken((string)$authorizationToken);

        return $this->findRestUserTransfer($restRequest, $oauthAccessTokenDataTransfer);
    }

    /**
     * @param string $authorizationToken
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenDataTransfer
     */
    protected function findUserByAccessToken(string $authorizationToken): OauthAccessTokenDataTransfer
    {
        [$type, $accessToken] = $this->extractToken($authorizationToken);

        $oauthTokenData = $this->oauthService->extractAccessTokenData($accessToken);

        return $oauthTokenData;
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
     * @param \Generated\Shared\Transfer\OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer
     *
     * @return \Generated\Shared\Transfer\RestUserTransfer|null
     */
    protected function findRestUserTransfer(
        RestRequestInterface $restRequest,
        OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer
    ): ?RestUserTransfer {
        if (!$oauthAccessTokenDataTransfer->getOauthUserId()) {
            return null;
        }

        return $this->mapRestUserTransfer($oauthAccessTokenDataTransfer, $restRequest);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestUserTransfer
     */
    protected function mapRestUserTransfer(
        OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer,
        RestRequestInterface $restRequest
    ): RestUserTransfer {
        $customerIdentifier = $this->utilEncodingService->decodeJson(
            $oauthAccessTokenDataTransfer->getOauthUserId(),
            true
        );

        $restUserTransfer = (new RestUserTransfer())
            ->fromArray($customerIdentifier, true)
            ->setNaturalIdentifier($customerIdentifier[static::KEY_CUSTOMER_REFERENCE])
            ->setSurrogateIdentifier($customerIdentifier[static::KEY_ID_CUSTOMER])
            ->setScopes($oauthAccessTokenDataTransfer->getOauthScopes());

        foreach ($this->restUserMapperPlugins as $restUserMapperPlugin) {
            $restUserTransfer = $restUserMapperPlugin->map($restUserTransfer, $restRequest);
        }

        return $restUserTransfer;
    }
}
