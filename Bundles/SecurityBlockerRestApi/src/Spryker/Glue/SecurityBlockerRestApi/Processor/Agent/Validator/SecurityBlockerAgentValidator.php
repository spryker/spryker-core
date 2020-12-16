<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SecurityBlockerRestApi\Processor\Agent\Validator;

use Generated\Shared\Transfer\RestAccessTokensAttributesTransfer;
use Generated\Shared\Transfer\RestAgentAccessTokensRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SecurityBlockerRestApi\Dependency\Client\SecurityBlockerRestApiToSecurityBlockerClientInterface;
use Spryker\Glue\SecurityBlockerRestApi\SecurityBlockerRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class SecurityBlockerAgentValidator implements SecurityBlockerAgentValidatorInterface
{
    /**
     * @uses \Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiConfig::RESOURCE_AGENT_ACCESS_TOKENS
     */
    protected const RESOURCE_AGENT_ACCESS_TOKENS = 'agent-access-tokens';

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

        /** @var \Generated\Shared\Transfer\RestAgentAccessTokensRequestAttributesTransfer $restAgentAccessTokensRequestAttributesTransfer */
        $restAgentAccessTokensRequestAttributesTransfer = $restRequest->getResource()->getAttributes();
        $securityCheckAuthContextTransfer = (new SecurityCheckAuthContextTransfer())
            ->setType(SecurityBlockerRestApiConfig::SECURITY_BLOCKER_AGENT_ENTITY_TYPE)
            ->setIp($restRequest->getHttpRequest()->getClientIp())
            ->setAccount($restAgentAccessTokensRequestAttributesTransfer->getUsername());

        $securityCheckAuthResponseTransfer = $this->securityBlockerClient
            ->getLoginAttempt($securityCheckAuthContextTransfer);

        if ($securityCheckAuthResponseTransfer->getIsSuccessful()) {
            return null;
        }

        return $this->createRestErrorCollectionTransfer();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    protected function isAuthenticationRequest(RestRequestInterface $restRequest): bool
    {
        return in_array($restRequest->getResource()->getType(), [static::RESOURCE_AGENT_ACCESS_TOKENS])
            && $restRequest->getHttpRequest()->getMethod() === 'POST';
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    protected function createRestErrorCollectionTransfer(): RestErrorCollectionTransfer
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_TOO_MANY_REQUESTS)
            ->setCode(SecurityBlockerRestApiConfig::ERROR_RESPONSE_CODE_ACCOUNT_BLOCKED)
            ->setDetail(sprintf(SecurityBlockerRestApiConfig::ERROR_RESPONSE_DETAIL_ACCOUNT_BLOCKED, '6'));

        return (new RestErrorCollectionTransfer())
            ->addRestError($restErrorMessageTransfer);
    }
}
