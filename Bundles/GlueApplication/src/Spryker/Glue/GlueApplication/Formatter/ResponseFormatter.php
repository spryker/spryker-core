<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Formatter;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;

class ResponseFormatter implements ResponseFormatterInterface
{
    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
     */
    protected array $defaultResponseFormatterPlugins;

    /**
     * @var array<\Spryker\Glue\GlueApplication\Formatter\Response\ResponseFormatterInterface>
     */
    protected array $responseFormatters;

    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface> $defaultResponseFormatterPlugins
     * @param array<\Spryker\Glue\GlueApplication\Formatter\Response\ResponseFormatterInterface> $responseFormatters
     */
    public function __construct(
        array $defaultResponseFormatterPlugins,
        array $responseFormatters
    ) {
        $this->defaultResponseFormatterPlugins = $defaultResponseFormatterPlugins;
        $this->responseFormatters = $responseFormatters;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication $apiApplication
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface|null $apiConventionPlugin
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface|null $resource
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function format(
        GlueResponseTransfer $glueResponseTransfer,
        GlueRequestTransfer $glueRequestTransfer,
        RequestFlowAwareApiApplication $apiApplication,
        ?ConventionPluginInterface $apiConventionPlugin,
        ?ResourceInterface $resource = null
    ): GlueResponseTransfer {
        $glueResponseTransfer = $this->formatResponse($glueResponseTransfer, $glueRequestTransfer, $apiConventionPlugin, $resource);
        $responseFormatterPlugins = $this->provideResponseFormatterPlugins($apiApplication, $apiConventionPlugin);

        return $this->executeResponseFormatterPlugins($glueResponseTransfer, $glueRequestTransfer, $responseFormatterPlugins);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface|null $apiConventionPlugin
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface|null $resource
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function formatResponse(
        GlueResponseTransfer $glueResponseTransfer,
        GlueRequestTransfer $glueRequestTransfer,
        ?ConventionPluginInterface $apiConventionPlugin,
        ?ResourceInterface $resource = null
    ): GlueResponseTransfer {
        if ($apiConventionPlugin !== null) {
            return $glueResponseTransfer;
        }

        foreach ($this->responseFormatters as $responseFormatter) {
            $glueResponseTransfer = $responseFormatter->format($glueResponseTransfer, $glueRequestTransfer, $resource);
        }

        return $glueResponseTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication $apiApplication
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface|null $apiConventionPlugin
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
     */
    protected function provideResponseFormatterPlugins(
        RequestFlowAwareApiApplication $apiApplication,
        ?ConventionPluginInterface $apiConventionPlugin
    ): array {
        $responseFormatterPlugins = $this->defaultResponseFormatterPlugins;
        if ($apiConventionPlugin !== null) {
            $responseFormatterPlugins = $apiConventionPlugin->provideResponseFormatterPlugins();
        }

        return array_merge($responseFormatterPlugins, $apiApplication->provideResponseFormatterPlugins());
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface> $responseFormatterPlugins
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function executeResponseFormatterPlugins(
        GlueResponseTransfer $glueResponseTransfer,
        GlueRequestTransfer $glueRequestTransfer,
        array $responseFormatterPlugins
    ): GlueResponseTransfer {
        foreach ($responseFormatterPlugins as $responseFormatterPlugin) {
            $glueResponseTransfer = $responseFormatterPlugin->format($glueResponseTransfer, $glueRequestTransfer);
        }

        return $glueResponseTransfer;
    }
}
