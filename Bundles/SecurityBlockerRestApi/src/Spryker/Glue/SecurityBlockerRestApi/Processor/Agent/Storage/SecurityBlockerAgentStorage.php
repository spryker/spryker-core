<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SecurityBlockerRestApi\Processor\Agent\Storage;

use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SecurityBlockerRestApi\Dependency\Client\SecurityBlockerRestApiToSecurityBlockerClientInterface;
use Spryker\Glue\SecurityBlockerRestApi\Processor\Checker\AuthenticationCheckerInterface;
use Spryker\Glue\SecurityBlockerRestApi\SecurityBlockerRestApiConfig;

class SecurityBlockerAgentStorage implements SecurityBlockerAgentStorageInterface
{
    /**
     * @uses \Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiConfig::RESPONSE_CODE_INVALID_LOGIN
     */
    protected const RESPONSE_CODE_INVALID_LOGIN = '4101';

    /**
     * @var \Spryker\Glue\SecurityBlockerRestApi\Dependency\Client\SecurityBlockerRestApiToSecurityBlockerClientInterface
     */
    protected $securityBlockerClient;

    /**
     * @var \Spryker\Glue\SecurityBlockerRestApi\Processor\Checker\AuthenticationCheckerInterface
     */
    protected $authenticationChecker;

    /**
     * @param \Spryker\Glue\SecurityBlockerRestApi\Dependency\Client\SecurityBlockerRestApiToSecurityBlockerClientInterface $securityBlockerClient
     * @param \Spryker\Glue\SecurityBlockerRestApi\Processor\Checker\AuthenticationCheckerInterface $authenticationChecker
     */
    public function __construct(
        SecurityBlockerRestApiToSecurityBlockerClientInterface $securityBlockerClient,
        AuthenticationCheckerInterface $authenticationChecker
    ) {
        $this->securityBlockerClient = $securityBlockerClient;
        $this->authenticationChecker = $authenticationChecker;
    }

    /**
     * @param string $action
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return void
     */
    public function incrementLoginAttemptCount(
        string $action,
        RestRequestInterface $restRequest,
        RestResponseInterface $restResponse
    ): void {
        if (
            !$this->authenticationChecker->isAuthenticationRequest($restRequest, SecurityBlockerRestApiConfig::RESOURCE_AGENT_ACCESS_TOKENS)
            || !$this->authenticationChecker->isFailedAuthenticationResponse($restResponse, static::RESPONSE_CODE_INVALID_LOGIN)
        ) {
            return;
        }

        $securityCheckAuthContextTransfer = $this->createSecurityCheckAuthContextTransfer($restRequest);

        $this->securityBlockerClient->incrementLoginAttemptCount($securityCheckAuthContextTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer
     */
    protected function createSecurityCheckAuthContextTransfer(RestRequestInterface $restRequest): SecurityCheckAuthContextTransfer
    {
        /** @var \Generated\Shared\Transfer\RestAccessTokensAttributesTransfer $restAccessTokensAttributesTransfer */
        $restAccessTokensAttributesTransfer = $restRequest->getResource()->getAttributes();

        return (new SecurityCheckAuthContextTransfer())
            ->setType(SecurityBlockerRestApiConfig::SECURITY_BLOCKER_AGENT_ENTITY_TYPE)
            ->setIp($restRequest->getHttpRequest()->getClientIp())
            ->setAccount($restAccessTokensAttributesTransfer->getUsername());
    }
}
