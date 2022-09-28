<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Builder;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface;

class RequestBuilder implements RequestBuilderInterface
{
    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    protected array $defaultRequestBuilderPlugins;

    /**
     * @var array<\Spryker\Glue\GlueApplication\Builder\Request\RequestBuilderInterface>
     */
    protected array $requestBuilders;

    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface> $defaultRequestBuilderPlugins
     * @param array<\Spryker\Glue\GlueApplication\Builder\Request\RequestBuilderInterface> $requestBuilders
     */
    public function __construct(
        array $defaultRequestBuilderPlugins,
        array $requestBuilders
    ) {
        $this->defaultRequestBuilderPlugins = $defaultRequestBuilderPlugins;
        $this->requestBuilders = $requestBuilders;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication $apiApplication
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface|null $apiConventionPlugin
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function build(
        GlueRequestTransfer $glueRequestTransfer,
        RequestFlowAwareApiApplication $apiApplication,
        ?ConventionPluginInterface $apiConventionPlugin
    ): GlueRequestTransfer {
        $glueRequestTransfer = $this->buildRequest($glueRequestTransfer, $apiConventionPlugin);
        $requestBuilderPlugins = $this->provideRequestBuilderPlugins($apiApplication, $apiConventionPlugin);

        return $this->executeRequestBuilderPlugins($glueRequestTransfer, $requestBuilderPlugins);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface|null $apiConventionPlugin
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function buildRequest(
        GlueRequestTransfer $glueRequestTransfer,
        ?ConventionPluginInterface $apiConventionPlugin
    ): GlueRequestTransfer {
        if ($apiConventionPlugin !== null) {
            return $glueRequestTransfer;
        }

        foreach ($this->requestBuilders as $requestBuilder) {
            $glueRequestTransfer = $requestBuilder->build($glueRequestTransfer);
        }

        return $glueRequestTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication $apiApplication
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface|null $apiConventionPlugin
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    protected function provideRequestBuilderPlugins(
        RequestFlowAwareApiApplication $apiApplication,
        ?ConventionPluginInterface $apiConventionPlugin
    ): array {
        $requestBuilderPlugins = $this->defaultRequestBuilderPlugins;
        if ($apiConventionPlugin) {
            $requestBuilderPlugins = $apiConventionPlugin->provideRequestBuilderPlugins();
        }

        return array_merge($requestBuilderPlugins, $apiApplication->provideRequestBuilderPlugins());
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface> $requestBuilderPlugins
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function executeRequestBuilderPlugins(
        GlueRequestTransfer $glueRequestTransfer,
        array $requestBuilderPlugins
    ): GlueRequestTransfer {
        foreach ($requestBuilderPlugins as $requestBuilderPlugin) {
            $glueRequestTransfer = $requestBuilderPlugin->build($glueRequestTransfer);
        }

        return $glueRequestTransfer;
    }
}
