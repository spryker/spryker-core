<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationAuthorizationConnector\Processor\AuthorizationChecker;

use Generated\Shared\Transfer\AuthorizationEntityTransfer;
use Generated\Shared\Transfer\AuthorizationIdentityTransfer;
use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RouteAuthorizationConfigTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\GlueApplicationAuthorizationConnector\Dependency\Client\GlueApplicationAuthorizationConnectorToAuthorizationClientInterface;
use Spryker\Shared\GlueApplicationAuthorizationConnector\GlueApplicationAuthorizationConnectorConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizationChecker implements AuthorizationCheckerInterface
{
    protected const MESSAGE_UNAUTHORIZED_REQUEST = 'Unauthorized request.';

    /**
     * @var \Spryker\Glue\GlueApplicationAuthorizationConnector\Dependency\Client\GlueApplicationAuthorizationConnectorToAuthorizationClientInterface
     */
    protected $authorizationClient;

    /**
     * @param \Spryker\Glue\GlueApplicationAuthorizationConnector\Dependency\Client\GlueApplicationAuthorizationConnectorToAuthorizationClientInterface $authorizationClient
     */
    public function __construct(GlueApplicationAuthorizationConnectorToAuthorizationClientInterface $authorizationClient)
    {
        $this->authorizationClient = $authorizationClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(RestRequestInterface $restRequest): ?RestErrorMessageTransfer
    {
        if (!$restRequest->getRestUser() || !$restRequest->getHttpRequest()->get(RequestConstantsInterface::ATTRIBUTE_IS_PROTECTED)) {
            return null;
        }

        $routeAuthorizationConfigTransfer = $restRequest->getHttpRequest()->get(GlueApplicationAuthorizationConnectorConfig::ATTRIBUTE_ROUTE_AUTHORIZATION_DEFAULT_CONFIGURATION);
        $routeAuthorizationConfigTransfers = $restRequest->getHttpRequest()->get(GlueApplicationAuthorizationConnectorConfig::ATTRIBUTE_ROUTE_AUTHORIZATION_CONFIGURATIONS, []);

        $method = $restRequest->getHttpRequest()->getMethod();

        if (array_key_exists($method, $routeAuthorizationConfigTransfers)) {
            $routeAuthorizationConfigTransfer = $routeAuthorizationConfigTransfers[$method];
        }

        if (!($routeAuthorizationConfigTransfer instanceof RouteAuthorizationConfigTransfer)) {
            return null;
        }

        $routeAuthorizationConfigTransfer
            ->requireApiCode()
            ->requireStrategy();

        /** @var string $strategy */
        $strategy = $routeAuthorizationConfigTransfer->getStrategy();
        $authorizationRequestTransfer = $this->createAuthorizationRequestTransfer($strategy, $restRequest);

        if (
            $method === Request::METHOD_GET &&
            (
                !$authorizationRequestTransfer->getEntity() ||
                !$authorizationRequestTransfer->getEntity()->getIdentifier()
            )
        ) {
            return null;
        }

        $authorizationResponseTransfer = $this->authorizationClient->authorize($authorizationRequestTransfer);

        if ($authorizationResponseTransfer->getIsAuthorized()) {
            return null;
        }

        $apiCode = $routeAuthorizationConfigTransfer->getApiCode();
        $apiMessage = $routeAuthorizationConfigTransfer->getApiMessage() ?? static::MESSAGE_UNAUTHORIZED_REQUEST;
        $httpStatusCode = $routeAuthorizationConfigTransfer->getHttpStatusCode() ?? Response::HTTP_FORBIDDEN;

        return (new RestErrorMessageTransfer())
            ->setCode($apiCode)
            ->setDetail($apiMessage)
            ->setStatus($httpStatusCode);
    }

    /**
     * @param string $strategy
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\AuthorizationRequestTransfer
     */
    protected function createAuthorizationRequestTransfer(string $strategy, RestRequestInterface $restRequest): AuthorizationRequestTransfer
    {
        /** @var \Generated\Shared\Transfer\RestUserTransfer $restUser */
        $restUser = $restRequest->getRestUser();

        $authorizationIdentityTransfer = (new AuthorizationIdentityTransfer())
            ->setIdentifier($restUser->getNaturalIdentifier());
        $authorizationEntityTransfer = (new AuthorizationEntityTransfer());

        $allResources = $restRequest->getHttpRequest()->get(RequestConstantsInterface::ATTRIBUTE_ALL_RESOURCES);
        if (count($allResources) > 0) {
            $firstResource = reset($allResources);
            $authorizationEntityTransfer
                ->setIdentifier($firstResource[RequestConstantsInterface::ATTRIBUTE_ID])
                ->setEntityType($firstResource[RequestConstantsInterface::ATTRIBUTE_TYPE])
                ->setData($allResources);
        }

        return (new AuthorizationRequestTransfer())
            ->setStrategy($strategy)
            ->setEntity($authorizationEntityTransfer)
            ->setIdentity($authorizationIdentityTransfer);
    }
}
