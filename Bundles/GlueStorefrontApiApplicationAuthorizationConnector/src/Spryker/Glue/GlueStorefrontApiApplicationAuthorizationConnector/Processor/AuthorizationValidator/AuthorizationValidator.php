<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\Processor\AuthorizationValidator;

use Generated\Shared\Transfer\AuthorizationEntityTransfer;
use Generated\Shared\Transfer\AuthorizationIdentityTransfer;
use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\AuthorizationResponseTransfer;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\RouteAuthorizationConfigTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\Dependency\Client\GlueStorefrontApiApplicationAuthorizationConnectorToAuthorizationClientInterface;
use Symfony\Component\HttpFoundation\Response;

class AuthorizationValidator implements AuthorizationValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_UNAUTHORIZED_REQUEST = 'Unauthorized request.';

    /**
     * @var \Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\Dependency\Client\GlueStorefrontApiApplicationAuthorizationConnectorToAuthorizationClientInterface
     */
    protected $authorizationClient;

    /**
     * @var array<\Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\ConfigExtractorStrategy\ConfigExtractorStrategyInterface>
     */
    protected $configExtractorStrategies = [];

    /**
     * @param \Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\Dependency\Client\GlueStorefrontApiApplicationAuthorizationConnectorToAuthorizationClientInterface $authorizationClient
     * @param array<\Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\ConfigExtractorStrategy\ConfigExtractorStrategyInterface> $configExtractorStrategies
     */
    public function __construct(
        GlueStorefrontApiApplicationAuthorizationConnectorToAuthorizationClientInterface $authorizationClient,
        array $configExtractorStrategies
    ) {
        $this->authorizationClient = $authorizationClient;
        $this->configExtractorStrategies = $configExtractorStrategies;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(GlueRequestTransfer $glueRequestTransfer, ResourceInterface $resource): GlueRequestValidationTransfer
    {
        $routeAuthorizationConfigTransfer = $this->extractRouteAuthorizationDefaultConfiguration($glueRequestTransfer, $resource);

        if ($routeAuthorizationConfigTransfer === null) {
            return $this->createDefaultGlueRequestValidationTransfer();
        }
        if (!$glueRequestTransfer->getRequestCustomer()) {
            return $this->createDefaultGlueRequestNotValidationTransfer();
        }

        $authorizationRequestTransfer = $this->createAuthorizationRequestTransfer($routeAuthorizationConfigTransfer->getStrategyOrFail(), $glueRequestTransfer);
        $authorizationResponseTransfer = $this->authorizationClient->authorize($authorizationRequestTransfer);

        return $this->createGlueRequestValidationTransfer($authorizationResponseTransfer, $routeAuthorizationConfigTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\RouteAuthorizationConfigTransfer|null
     */
    protected function extractRouteAuthorizationDefaultConfiguration(
        GlueRequestTransfer $glueRequestTransfer,
        ResourceInterface $resource
    ): ?RouteAuthorizationConfigTransfer {
        $routeAuthorizationConfigTransfer = null;

        foreach ($this->configExtractorStrategies as $configExtractorStrategy) {
            if ($configExtractorStrategy->isApplicable($resource)) {
                $routeAuthorizationConfigTransfer = $configExtractorStrategy->extractRouteAuthorizationConfigTransfer(
                    $glueRequestTransfer,
                    $resource,
                );

                if ($routeAuthorizationConfigTransfer !== null) {
                    return $routeAuthorizationConfigTransfer;
                }
            }
        }

        return $routeAuthorizationConfigTransfer;
    }

    /**
     * @param string $strategy
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return mixed
     */
    protected function createAuthorizationRequestTransfer(string $strategy, GlueRequestTransfer $glueRequestTransfer)
    {
        $authorizationEntityTransfer = new AuthorizationEntityTransfer();
        $authorizationIdentityTransfer = (new AuthorizationIdentityTransfer())
            ->setIdentifier($glueRequestTransfer->getRequestCustomerOrFail()->getNaturalIdentifierOrFail());

        $glueResourceTransfer = $glueRequestTransfer->getResource();

        if ($glueResourceTransfer !== null) {
            $authorizationEntityTransfer
                ->setIdentifier($glueResourceTransfer->getIdOrFail())
                ->setEntityType($glueResourceTransfer->getTypeOrFail());
        }

        return (new AuthorizationRequestTransfer())
            ->setIdentity($authorizationIdentityTransfer)
            ->setStrategy($strategy)
            ->setEntity($authorizationEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AuthorizationResponseTransfer $authorizationResponseTransfer
     * @param \Generated\Shared\Transfer\RouteAuthorizationConfigTransfer $routeAuthorizationConfigTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    protected function createGlueRequestValidationTransfer(
        AuthorizationResponseTransfer $authorizationResponseTransfer,
        RouteAuthorizationConfigTransfer $routeAuthorizationConfigTransfer
    ): GlueRequestValidationTransfer {
        $glueRequestValidationTransfer = new GlueRequestValidationTransfer();

        if ($authorizationResponseTransfer->getIsAuthorized()) {
            $glueRequestValidationTransfer->setIsValid(true);

            return $glueRequestValidationTransfer;
        }

        return $this->createDefaultGlueRequestNotValidationTransfer(
            $routeAuthorizationConfigTransfer->getApiMessage(),
            $routeAuthorizationConfigTransfer->getHttpStatusCode(),
        );
    }

    /**
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    protected function createDefaultGlueRequestValidationTransfer(): GlueRequestValidationTransfer
    {
        return (new GlueRequestValidationTransfer())->setIsValid(true);
    }

    /**
     * @param string|null $validationErrorMessage
     * @param int|null $status
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    protected function createDefaultGlueRequestNotValidationTransfer(?string $validationErrorMessage = null, ?int $status = null): GlueRequestValidationTransfer
    {
        $validationErrorMessage = $validationErrorMessage ?? static::ERROR_MESSAGE_UNAUTHORIZED_REQUEST;
        $status = $status ?? Response::HTTP_FORBIDDEN;

        $glueErrorTransfer = (new GlueErrorTransfer())
            ->setStatus($status)
            ->setMessage($validationErrorMessage);

        return (new GlueRequestValidationTransfer())->setIsValid(false)
            ->setValidationError($validationErrorMessage)
            ->addError($glueErrorTransfer)
            ->setStatus($status);
    }
}
