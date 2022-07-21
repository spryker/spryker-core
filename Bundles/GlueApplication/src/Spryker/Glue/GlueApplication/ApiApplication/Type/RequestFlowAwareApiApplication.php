<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\ApiApplication\Type;

use Spryker\Glue\Kernel\FactoryResolverAwareTrait;
use Spryker\Shared\Application\Application;

/**
 * ApiApplication classes extending this class are not plugins, they extend {@link \Spryker\Shared\Application\Application}
 * to hold the execution flow of the API application. This abstract class makes it easy for new API applications to follow the
 * standardized GlueApplication request flow using Spryker transfer objects as input/output.
 */
abstract class RequestFlowAwareApiApplication extends Application
{
    use FactoryResolverAwareTrait;

    /**
     * Specification:
     * - Provide a set of builders for the API application.
     * - Plugins receive the `GlueRequestTransfer`.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    abstract public function provideRequestBuilderPlugins(): array;

    /**
     * Specification:
     * - Provide a set of validators for the API application.
     * - Will be run before the routing is executed.
     * - Plugins receive the `GlueRequestTransfer`.
     *
     * @see {@link \Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication::provideRequestBuilderPlugins()}
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    abstract public function provideRequestValidatorPlugins(): array;

    /**
     * Specification:
     * - Provide a set of validators for the API application.
     * - Will be run after the routing is executed.
     * - Plugins receive the `\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface` and `GlueRequestTransfer`.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface>
     */
    abstract public function provideRequestAfterRoutingValidatorPlugins(): array;

    /**
     * Specification:
     * - Provide a set of formatters for the API application.
     * - Plugins receive the `GlueRequestTransfer`.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
     */
    abstract public function provideResponseFormatterPlugins(): array;
}
