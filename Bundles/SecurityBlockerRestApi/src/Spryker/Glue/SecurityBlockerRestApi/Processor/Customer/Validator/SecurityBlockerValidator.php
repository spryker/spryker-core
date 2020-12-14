<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SecurityBlockerRestApi\Processor\Customer\Validator;

use Generated\Shared\Transfer\AuthContextTransfer;
use Generated\Shared\Transfer\RestAccessTokensAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SecurityBlockerRestApi\Dependency\Client\SecurityBlockerRestApiToSecurityBlockerClientInterface;
use Spryker\Glue\SecurityBlockerRestApi\SecurityBlockerRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class SecurityBlockerValidator implements SecurityBlockerValidatorInterface
{
    /**
     * @uses \Spryker\Glue\AuthRestApi\AuthRestApiConfig::RESOURCE_ACCESS_TOKENS
     */
    protected const RESOURCE_ACCESS_TOKENS = 'access-tokens';

    /**
     * @var \Spryker\Glue\SecurityBlockerRestApi\Dependency\Client\SecurityBlockerRestApiToSecurityBlockerClientInterface
     */
    protected $securityBlockerClient;

    /**
     * @param \Spryker\Glue\SecurityBlockerRestApi\Dependency\Client\SecurityBlockerRestApiToSecurityBlockerClientInterface $securityBlockerClient
     */
    public function __construct(SecurityBlockerRestApiToSecurityBlockerClientInterface $securityBlockerClient)
    {
        $this->securityBlockerClient = $securityBlockerClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    public function isAccountBlocked(RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
    {
        if (!$this->isAuthenticationRequest($restRequest)) {
            return null;
        }

        /** @var \Generated\Shared\Transfer\RestAccessTokensAttributesTransfer $restAccessTokensAttributesTransfer */
        $restAccessTokensAttributesTransfer = $restRequest->getResource()->getAttributes();
        $authContextTransfer = (new AuthContextTransfer())
            ->setAccount(SecurityBlockerRestApiConfig::SECURITY_BLOCKER_CUSTOMER_ENTITY_TYPE)
            ->setIp($restRequest->getHttpRequest()->getClientIp())
            ->setAccount($restAccessTokensAttributesTransfer->getUsername());

        $authResponseTransfer = $this->securityBlockerClient->getLoginAttempt($authContextTransfer);

        if ($authResponseTransfer->getIsSuccessful()) {
            return null;
        }

        return $this->createRestErrorCollectionTransfer($restAccessTokensAttributesTransfer, $restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    protected function isAuthenticationRequest(RestRequestInterface $restRequest): bool
    {
        return in_array($restRequest->getResource()->getType(), [static::RESOURCE_ACCESS_TOKENS])
            && $restRequest->getHttpRequest()->getMethod() === 'POST';
    }

    /**
     * @param \Generated\Shared\Transfer\RestAccessTokensAttributesTransfer $restAccessTokensAttributesTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    protected function createRestErrorCollectionTransfer(
        RestAccessTokensAttributesTransfer $restAccessTokensAttributesTransfer,
        RestRequestInterface $restRequest
    ): RestErrorCollectionTransfer {
        $detail = sprintf(
            SecurityBlockerRestApiConfig::ERROR_RESPONSE_DETAIL_ACCOUNT_BLOCKED,
            $restAccessTokensAttributesTransfer->getUsername(),
            $restRequest->getHttpRequest()->getClientIp()
        );

        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_TOO_MANY_REQUESTS)
            ->setCode(SecurityBlockerRestApiConfig::ERROR_RESPONSE_CODE_ACCOUNT_BLOCKED)
            ->setDetail($detail);

        return (new RestErrorCollectionTransfer())
            ->addRestError($restErrorMessageTransfer);
    }
}
