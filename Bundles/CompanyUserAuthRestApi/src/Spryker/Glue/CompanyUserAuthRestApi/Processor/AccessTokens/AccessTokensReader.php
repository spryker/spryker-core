<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserAuthRestApi\Processor\AccessTokens;

use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\RestCompanyUserAccessTokensAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestTokenResponseAttributesTransfer;
use Spryker\Glue\CompanyUserAuthRestApi\CompanyUserAuthRestApiConfig;
use Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class AccessTokensReader implements AccessTokensReaderInterface
{
    /**
     * @var \Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface
     */
    protected $oauthClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CompanyUserAuthRestApi\CompanyUserAuthRestApiConfig
     */
    protected $authRestApiConfig;

    /**
     * @param \Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface $oauthClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CompanyUserAuthRestApi\CompanyUserAuthRestApiConfig $authRestApiConfig
     */
    public function __construct(
        AuthRestApiToOauthClientInterface $oauthClient,
        RestResourceBuilderInterface $restResourceBuilder,
        CompanyUserAuthRestApiConfig $authRestApiConfig
    ) {
        $this->oauthClient = $oauthClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->authRestApiConfig = $authRestApiConfig;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCompanyUserAccessTokensAttributesTransfer $restCompanyUserAccessTokensAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function processAccessTokenRequest(
        RestRequestInterface $restRequest,
        RestCompanyUserAccessTokensAttributesTransfer $restCompanyUserAccessTokensAttributesTransfer
    ): RestResponseInterface {
        $oauthRequestTransfer = (new OauthRequestTransfer())
            ->setIdCompanyUser($restCompanyUserAccessTokensAttributesTransfer->getIdCompanyUser())
            ->setCustomerReference($restRequest->getUser()->getNaturalIdentifier())
            ->setGrantType(CompanyUserAuthRestApiConfig::CLIENT_GRANT_USER)
            ->setClientId($this->authRestApiConfig->getClientId())
            ->setClientSecret($this->authRestApiConfig->getClientSecret());

        $oauthResponseTransfer = $this->oauthClient->processAccessTokenRequest($oauthRequestTransfer);

        if (!$oauthResponseTransfer->getIsValid()) {
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setCode(CompanyUserAuthRestApiConfig::RESPONSE_INVALID_LOGIN)
                ->setStatus(Response::HTTP_UNAUTHORIZED)
                ->setDetail('Failed to authenticate user.');

            $response = $this->restResourceBuilder->createRestResponse();
            $response->addError($restErrorTransfer);

            return $response;
        }

        $restTokenAttributesTransfer = new RestTokenResponseAttributesTransfer();
        $restTokenAttributesTransfer->fromArray($oauthResponseTransfer->toArray(), true);

        $accessTokenResource = $this->restResourceBuilder
            ->createRestResource(
                CompanyUserAuthRestApiConfig::RESOURCE_COMPANY_USER_ACCESS_TOKENS,
                null,
                $restTokenAttributesTransfer
            );

        $response = $this->restResourceBuilder->createRestResponse();
        $response->addResource($accessTokenResource);

        return $response;
    }
}
