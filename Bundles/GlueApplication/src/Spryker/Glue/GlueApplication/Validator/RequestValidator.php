<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Validator;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;

class RequestValidator implements RequestValidatorInterface
{
    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    protected array $defaultRequestValidatorPlugins;

    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface>
     */
    protected array $defaultRequestAfterRoutingValidatorPlugins;

    /**
     * @var array<\Spryker\Glue\GlueApplication\Validator\Request\RequestValidatorInterface>
     */
    protected array $requestValidators;

    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface> $defaultRequestValidatorPlugins
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface> $defaultRequestAfterRoutingValidatorPlugins
     * @param array<\Spryker\Glue\GlueApplication\Validator\Request\RequestValidatorInterface> $requestValidators
     */
    public function __construct(
        array $defaultRequestValidatorPlugins,
        array $defaultRequestAfterRoutingValidatorPlugins,
        array $requestValidators
    ) {
        $this->defaultRequestValidatorPlugins = $defaultRequestValidatorPlugins;
        $this->defaultRequestAfterRoutingValidatorPlugins = $defaultRequestAfterRoutingValidatorPlugins;
        $this->requestValidators = $requestValidators;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication $apiApplication
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface|null $apiConventionPlugin
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(
        GlueRequestTransfer $glueRequestTransfer,
        RequestFlowAwareApiApplication $apiApplication,
        ?ConventionPluginInterface $apiConventionPlugin
    ): GlueRequestValidationTransfer {
        $glueRequestValidationTransfer = $this->validateRequest(
            $glueRequestTransfer,
            $apiConventionPlugin,
        );

        if (!$glueRequestValidationTransfer->getIsValid()) {
            return $glueRequestValidationTransfer;
        }

        $requestValidatorPlugins = $this->provideRequestValidatorPlugins(
            $apiApplication,
            $apiConventionPlugin,
        );
        $glueRequestValidationTransfer = $this->executeRequestValidatorPlugins(
            $glueRequestTransfer,
            $glueRequestValidationTransfer,
            $requestValidatorPlugins,
        );

        if (!$glueRequestValidationTransfer->getIsValid()) {
            return $glueRequestValidationTransfer;
        }

        return (new GlueRequestValidationTransfer())->setIsValid(true);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     * @param \Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication $apiApplication
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface|null $apiConventionPlugin
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validateAfterRouting(
        GlueRequestTransfer $glueRequestTransfer,
        ResourceInterface $resource,
        RequestFlowAwareApiApplication $apiApplication,
        ?ConventionPluginInterface $apiConventionPlugin
    ): GlueRequestValidationTransfer {
        $requestAfterRoutingValidatorPlugins = $this->provideRequestAfterRoutingValidatorPlugins(
            $apiApplication,
            $apiConventionPlugin,
        );

        $glueRequestValidationTransfer = $this->executeRequestAfterRoutingValidatorPlugins(
            $glueRequestTransfer,
            $resource,
            $requestAfterRoutingValidatorPlugins,
        );

        if (!$glueRequestValidationTransfer->getIsValid()) {
            return $glueRequestValidationTransfer;
        }

        return (new GlueRequestValidationTransfer())->setIsValid(true);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface|null $apiConventionPlugin
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    protected function validateRequest(
        GlueRequestTransfer $glueRequestTransfer,
        ?ConventionPluginInterface $apiConventionPlugin
    ): GlueRequestValidationTransfer {
        $glueRequestValidationTransfer = (new GlueRequestValidationTransfer())->setIsValid(true);

        if ($apiConventionPlugin !== null) {
            return $glueRequestValidationTransfer;
        }

        foreach ($this->requestValidators as $requestValidator) {
            $glueRequestValidationTransfer = $requestValidator->validate($glueRequestTransfer);

            if ($glueRequestValidationTransfer->getIsValid()) {
                continue;
            }

            return $glueRequestValidationTransfer;
        }

        return $glueRequestValidationTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication $apiApplication
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface|null $apiConventionPlugin
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    protected function provideRequestValidatorPlugins(
        RequestFlowAwareApiApplication $apiApplication,
        ?ConventionPluginInterface $apiConventionPlugin
    ): array {
        $requestValidatorPlugins = $this->defaultRequestValidatorPlugins;
        if ($apiConventionPlugin !== null) {
            $requestValidatorPlugins = $apiConventionPlugin->provideRequestValidatorPlugins();
        }

        return array_merge($requestValidatorPlugins, $apiApplication->provideRequestValidatorPlugins());
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\GlueRequestValidationTransfer $glueRequestValidationTransfer
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface> $requestValidatorPlugins
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    protected function executeRequestValidatorPlugins(
        GlueRequestTransfer $glueRequestTransfer,
        GlueRequestValidationTransfer $glueRequestValidationTransfer,
        array $requestValidatorPlugins
    ): GlueRequestValidationTransfer {
        foreach ($requestValidatorPlugins as $requestValidatorPlugin) {
            $glueRequestValidationTransfer = $requestValidatorPlugin->validate($glueRequestTransfer);

            if ($glueRequestValidationTransfer->getIsValid()) {
                continue;
            }

            return $glueRequestValidationTransfer;
        }

        return $glueRequestValidationTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication $apiApplication
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface|null $apiConventionPlugin
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface>
     */
    protected function provideRequestAfterRoutingValidatorPlugins(
        RequestFlowAwareApiApplication $apiApplication,
        ?ConventionPluginInterface $apiConventionPlugin
    ): array {
        $requestAfterRoutingValidatorPlugins = $this->defaultRequestAfterRoutingValidatorPlugins;
        if ($apiConventionPlugin !== null) {
            $requestAfterRoutingValidatorPlugins = $apiConventionPlugin->provideRequestAfterRoutingValidatorPlugins();
        }

        return array_merge($requestAfterRoutingValidatorPlugins, $apiApplication->provideRequestAfterRoutingValidatorPlugins());
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface> $requestAfterRoutingValidatorPlugins
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    protected function executeRequestAfterRoutingValidatorPlugins(
        GlueRequestTransfer $glueRequestTransfer,
        ResourceInterface $resource,
        array $requestAfterRoutingValidatorPlugins
    ): GlueRequestValidationTransfer {
        $glueRequestValidationTransfer = (new GlueRequestValidationTransfer())->setIsValid(true);
        foreach ($requestAfterRoutingValidatorPlugins as $requestAfterRoutingValidatorPlugin) {
            $glueRequestValidationTransfer = $requestAfterRoutingValidatorPlugin->validate($glueRequestTransfer, $resource);

            if ($glueRequestValidationTransfer->getIsValid()) {
                continue;
            }

            return $glueRequestValidationTransfer;
        }

        return $glueRequestValidationTransfer;
    }
}
