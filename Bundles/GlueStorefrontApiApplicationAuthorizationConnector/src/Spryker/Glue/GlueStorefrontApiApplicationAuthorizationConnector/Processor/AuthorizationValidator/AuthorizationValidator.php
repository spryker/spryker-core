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
use Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\GlueStorefrontApiApplicationAuthorizationConnectorConfig;
use Symfony\Component\HttpFoundation\Response;

class AuthorizationValidator implements AuthorizationValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_UNAUTHORIZED_REQUEST = 'Unauthorized request.';

    /**
     * @var string
     */
    protected const PROTECTED_PATH_STRATEGY_NAME = 'ProtectedPath';

    /**
     * @var string
     */
    protected const METHOD = 'method';

    /**
     * @var string
     */
    protected const PATH = 'path';

    /**
     * @var string
     */
    protected const GLUE_REQUEST_CUSTOMER = 'glueRequestCustomer';

    /**
     * @var \Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\Dependency\Client\GlueStorefrontApiApplicationAuthorizationConnectorToAuthorizationClientInterface
     */
    protected $authorizationClient;

    /**
     * @var array<\Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\ConfigExtractorStrategy\ConfigExtractorStrategyInterface>
     */
    protected $configExtractorStrategies = [];

    /**
     * @var \Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\GlueStorefrontApiApplicationAuthorizationConnectorConfig
     */
    protected $glueStorefrontApiApplicationAuthorizationConfig;

    /**
     * @param \Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\Dependency\Client\GlueStorefrontApiApplicationAuthorizationConnectorToAuthorizationClientInterface $authorizationClient
     * @param array<\Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\ConfigExtractorStrategy\ConfigExtractorStrategyInterface> $configExtractorStrategies
     * @param \Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\GlueStorefrontApiApplicationAuthorizationConnectorConfig $glueStorefrontApiApplicationAuthorizationConfig
     */
    public function __construct(
        GlueStorefrontApiApplicationAuthorizationConnectorToAuthorizationClientInterface $authorizationClient,
        array $configExtractorStrategies,
        GlueStorefrontApiApplicationAuthorizationConnectorConfig $glueStorefrontApiApplicationAuthorizationConfig
    ) {
        $this->authorizationClient = $authorizationClient;
        $this->configExtractorStrategies = $configExtractorStrategies;
        $this->glueStorefrontApiApplicationAuthorizationConfig = $glueStorefrontApiApplicationAuthorizationConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(GlueRequestTransfer $glueRequestTransfer, ResourceInterface $resource): GlueRequestValidationTransfer
    {
        $routeAuthorizationConfigTransfers = $this->extractRouteAuthorizationDefaultConfiguration($glueRequestTransfer, $resource);

        $authorizationRequestTransfer = $this->createAuthorizationRequestTransfer($routeAuthorizationConfigTransfers, $glueRequestTransfer);
        $authorizationResponseTransfer = $this->authorizationClient->authorize($authorizationRequestTransfer);

        return $this->createGlueRequestValidationTransfer($authorizationResponseTransfer, $routeAuthorizationConfigTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     *
     * @return array<\Generated\Shared\Transfer\RouteAuthorizationConfigTransfer>
     */
    protected function extractRouteAuthorizationDefaultConfiguration(
        GlueRequestTransfer $glueRequestTransfer,
        ResourceInterface $resource
    ): array {
        $routeAuthorizationConfigTransfers = [];

        foreach ($this->configExtractorStrategies as $configExtractorStrategy) {
            if ($configExtractorStrategy->isApplicable($resource)) {
                $routeAuthorizationConfigTransfer = $configExtractorStrategy->extractRouteAuthorizationConfigTransfer(
                    $glueRequestTransfer,
                    $resource,
                );

                if ($routeAuthorizationConfigTransfer !== null) {
                    $routeAuthorizationConfigTransfers[] = $routeAuthorizationConfigTransfer;
                }
            }
        }

        if ($this->glueStorefrontApiApplicationAuthorizationConfig->getProtectedPaths() !== null) {
            $routeAuthorizationConfigTransfers[] = (new RouteAuthorizationConfigTransfer())
                ->addStrategy(static::PROTECTED_PATH_STRATEGY_NAME)
                ->setApiMessage(static::ERROR_MESSAGE_UNAUTHORIZED_REQUEST)
                ->setHttpStatusCode(Response::HTTP_FORBIDDEN);
        }

        return $routeAuthorizationConfigTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\RouteAuthorizationConfigTransfer> $routeAuthorizationConfigTransfers
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return mixed
     */
    protected function createAuthorizationRequestTransfer(array $routeAuthorizationConfigTransfers, GlueRequestTransfer $glueRequestTransfer)
    {
        $authorizationEntityTransfer = (new AuthorizationEntityTransfer())->setData([
            static::METHOD => $glueRequestTransfer->getMethod(),
            static::PATH => $glueRequestTransfer->getPath(),
            static::GLUE_REQUEST_CUSTOMER => $glueRequestTransfer->getRequestCustomer(),
        ]);
        $authorizationIdentityTransfer = new AuthorizationIdentityTransfer();
        if ($glueRequestTransfer->getRequestCustomer() !== null) {
            $authorizationIdentityTransfer->setIdentifier($glueRequestTransfer->getRequestCustomer()->getNaturalIdentifier());
        }

        $glueResourceTransfer = $glueRequestTransfer->getResource();

        if ($glueResourceTransfer !== null) {
            $authorizationEntityTransfer
                ->setIdentifier($glueResourceTransfer->getId())
                ->setEntityType($glueResourceTransfer->getType());
        }
        $strategies = [];
        foreach ($routeAuthorizationConfigTransfers as $routeAuthorizationConfigTransfer) {
            $strategies = array_merge($strategies, $routeAuthorizationConfigTransfer->getStrategies());
        }

        $authorizationRequestTransfer = (new AuthorizationRequestTransfer())
            ->setIdentity($authorizationIdentityTransfer)
            ->setStrategies(array_unique($strategies))
            ->setEntity($authorizationEntityTransfer);

        return $authorizationRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AuthorizationResponseTransfer $authorizationResponseTransfer
     * @param array<\Generated\Shared\Transfer\RouteAuthorizationConfigTransfer> $routeAuthorizationConfigTransfers
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    protected function createGlueRequestValidationTransfer(
        AuthorizationResponseTransfer $authorizationResponseTransfer,
        array $routeAuthorizationConfigTransfers
    ): GlueRequestValidationTransfer {
        if (!$authorizationResponseTransfer->getIsAuthorized()) {
            return $this->getGlueRequestValidationTransferFromConfig($authorizationResponseTransfer, $routeAuthorizationConfigTransfers);
        }

        $glueRequestValidationTransfer = new GlueRequestValidationTransfer();
        $glueRequestValidationTransfer->setIsValid(true);

        return $glueRequestValidationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AuthorizationResponseTransfer $authorizationResponseTransfer
     * @param array<\Generated\Shared\Transfer\RouteAuthorizationConfigTransfer> $routeAuthorizationConfigTransfers
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    protected function getGlueRequestValidationTransferFromConfig(
        AuthorizationResponseTransfer $authorizationResponseTransfer,
        array $routeAuthorizationConfigTransfers
    ): GlueRequestValidationTransfer {
        foreach ($routeAuthorizationConfigTransfers as $routeAuthorizationConfigTransfer) {
            $failedStrategy = $authorizationResponseTransfer->getFailedStrategyOrFail();

            if (in_array($failedStrategy, $routeAuthorizationConfigTransfer->getStrategies())) {
                return $this->createDefaultGlueRequestNotValidationTransfer(
                    $routeAuthorizationConfigTransfer->getApiMessage(),
                    $routeAuthorizationConfigTransfer->getHttpStatusCode(),
                );
            }
        }

        return $this->createDefaultGlueRequestNotValidationTransfer();
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
