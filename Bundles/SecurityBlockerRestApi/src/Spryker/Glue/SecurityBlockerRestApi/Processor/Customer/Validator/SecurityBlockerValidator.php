<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SecurityBlockerRestApi\Processor\Customer\Validator;

use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SecurityBlockerRestApi\Dependency\Client\SecurityBlockerRestApiToSecurityBlockerClientInterface;
use Spryker\Glue\SecurityBlockerRestApi\Processor\Builder\RestErrorCollectionBuilderInterface;
use Spryker\Glue\SecurityBlockerRestApi\Processor\Checker\AuthenticationCheckerInterface;
use Spryker\Glue\SecurityBlockerRestApi\SecurityBlockerRestApiConfig;

class SecurityBlockerValidator implements SecurityBlockerValidatorInterface
{
    /**
     * @var \Spryker\Glue\SecurityBlockerRestApi\Dependency\Client\SecurityBlockerRestApiToSecurityBlockerClientInterface
     */
    protected $securityBlockerClient;

    /**
     * @var \Spryker\Glue\SecurityBlockerRestApi\Processor\Checker\AuthenticationCheckerInterface
     */
    protected $authenticationChecker;

    /**
     * @var \Spryker\Glue\SecurityBlockerRestApi\Processor\Builder\RestErrorCollectionBuilderInterface
     */
    protected $restErrorCollectionBuilder;

    /**
     * @param \Spryker\Glue\SecurityBlockerRestApi\Dependency\Client\SecurityBlockerRestApiToSecurityBlockerClientInterface $securityBlockerClient
     * @param \Spryker\Glue\SecurityBlockerRestApi\Processor\Checker\AuthenticationCheckerInterface $authenticationChecker
     * @param \Spryker\Glue\SecurityBlockerRestApi\Processor\Builder\RestErrorCollectionBuilderInterface $restErrorCollectionBuilder
     */
    public function __construct(
        SecurityBlockerRestApiToSecurityBlockerClientInterface $securityBlockerClient,
        AuthenticationCheckerInterface $authenticationChecker,
        RestErrorCollectionBuilderInterface $restErrorCollectionBuilder
    ) {
        $this->securityBlockerClient = $securityBlockerClient;
        $this->authenticationChecker = $authenticationChecker;
        $this->restErrorCollectionBuilder = $restErrorCollectionBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    public function isAccountBlocked(RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
    {
        if (!$this->authenticationChecker->isAuthenticationRequest($restRequest, SecurityBlockerRestApiConfig::RESOURCE_ACCESS_TOKENS)) {
            return null;
        }

        $securityCheckAuthContextTransfer = $this->createSecurityCheckAuthContextTransfer($restRequest);

        $securityCheckAuthResponseTransfer = $this->securityBlockerClient
            ->isAccountBlocked($securityCheckAuthContextTransfer);

        if (!$securityCheckAuthResponseTransfer->getIsBlocked()) {
            return null;
        }

        return $this->restErrorCollectionBuilder->createRestErrorCollectionTransfer(
            $securityCheckAuthResponseTransfer,
            $restRequest->getMetadata()->getLocale()
        );
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
            ->setType(SecurityBlockerRestApiConfig::SECURITY_BLOCKER_CUSTOMER_ENTITY_TYPE)
            ->setIp($restRequest->getHttpRequest()->getClientIp())
            ->setAccount($restAccessTokensAttributesTransfer->getUsername());
    }
}
