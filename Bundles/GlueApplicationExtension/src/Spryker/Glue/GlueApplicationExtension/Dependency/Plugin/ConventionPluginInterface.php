<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\GlueRequestTransfer;

/**
 * Use this plugin interface to implement an API convention.
 */
interface ConventionPluginInterface
{
    /**
     * Specification:
     * - Checks if the convention is applicable to the current request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(GlueRequestTransfer $glueRequestTransfer): bool;

    /**
     * Specification:
     * - Returns convention name.
     * - Should be unique among the conventions wired for the project simultaneously.
     *
     * @api
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Specification:
     * - Returns a descendant of the {@link \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface}
     * that is used by this convention.
     *
     * @api
     *
     * @return string
     */
    public function getResourceType(): string;

    /**
     * Specification:
     * - Provide a set of builders for the convention.
     * - Plugins receive the `GlueRequestTransfer`.
     *
     * @api
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    public function provideRequestBuilderPlugins(): array;

    /**
     * Specification:
     * - Provide a set of validators for the convention.
     * - Will be run before the routing is executed.
     * - Plugins receive the `GlueRequestTransfer`.
     *
     * @api
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    public function provideRequestValidatorPlugins(): array;

    /**
     * Specification:
     * - Provide a set of validators for the convention.
     * - Will be run after the routing is executed.
     * - Plugins receive the `\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface` and `GlueRequestTransfer`.
     *
     * @api
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface>
     */
    public function provideRequestAfterRoutingValidatorPlugins(): array;

    /**
     * Specification:
     * - Provide a set of formatters for the convention.
     * - Plugins receive the `GlueRequestTransfer`.
     *
     * @api
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
     */
    public function provideResponseFormatterPlugins(): array;
}
