<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\CustomRoutesContextTransfer;
use Generated\Shared\Transfer\ResourceContextTransfer;

class OpenApiSpecificationPathFormatter implements OpenApiSchemaFormatterInterface
{
    /**
     * @var array<\Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Processor\PathMethodFormatterInterface>
     */
    protected $pathMethodFormatters;

    /**
     * @var array<\Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Processor\CustomPathMethodFormatterInterface>
     */
    protected $customRouteFormatters;

    /**
     * @var array<\Spryker\Glue\DocumentationGeneratorOpenApiExtension\Dependency\Plugin\OpenApiSchemaFormatterPluginInterface>
     */
    protected $openApiSchemaFormatterPlugins;

    /**
     * @param array<\Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Processor\PathMethodFormatterInterface> $pathMethodFormatters
     * @param array<\Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Processor\CustomPathMethodFormatterInterface> $customRouteFormatters
     * @param array<\Spryker\Glue\DocumentationGeneratorOpenApiExtension\Dependency\Plugin\OpenApiSchemaFormatterPluginInterface> $openApiSchemaFormatterPlugins
     */
    public function __construct(array $pathMethodFormatters, array $customRouteFormatters, array $openApiSchemaFormatterPlugins)
    {
        $this->pathMethodFormatters = $pathMethodFormatters;
        $this->customRouteFormatters = $customRouteFormatters;
        $this->openApiSchemaFormatterPlugins = $openApiSchemaFormatterPlugins;
    }

    /**
     * @param array<mixed> $formattedData
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return array<mixed>
     */
    public function format(array $formattedData, ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer): array
    {
        foreach ($apiApplicationSchemaContextTransfer->getResourceContexts() as $resourceContext) {
            $formattedData = $this->applyPathMethodFormatters($formattedData, $resourceContext);
        }

        foreach ($apiApplicationSchemaContextTransfer->getCustomRoutesContexts() as $customRoutesContext) {
            $formattedData = $this->applyCustomRouteFormatters($formattedData, $customRoutesContext);
        }

        $formattedData = $this->executeOpenApiSchemaFormatterPlugins($formattedData, $apiApplicationSchemaContextTransfer);

        return $formattedData;
    }

    /**
     * @param array<mixed> $formattedData
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return array<mixed>
     */
    protected function executeOpenApiSchemaFormatterPlugins(array $formattedData, ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer): array
    {
        foreach ($this->openApiSchemaFormatterPlugins as $openApiSchemaFormatterPlugin) {
            $formattedData = $openApiSchemaFormatterPlugin->format($formattedData, $apiApplicationSchemaContextTransfer);
        }

        return $formattedData;
    }

    /**
     * @param array<mixed> $formattedData
     * @param \Generated\Shared\Transfer\ResourceContextTransfer $resourceContextTransfer
     *
     * @return array<mixed>
     */
    protected function applyPathMethodFormatters(array $formattedData, ResourceContextTransfer $resourceContextTransfer): array
    {
        foreach ($this->pathMethodFormatters as $formatterPlugin) {
            $formattedData = $formatterPlugin->format($resourceContextTransfer, $formattedData);
        }

        return $formattedData;
    }

    /**
     * @param array<mixed> $formattedData
     * @param \Generated\Shared\Transfer\CustomRoutesContextTransfer $routesContext
     *
     * @return array<mixed>
     */
    protected function applyCustomRouteFormatters(array $formattedData, CustomRoutesContextTransfer $routesContext): array
    {
        foreach ($this->customRouteFormatters as $formatterPlugin) {
            $formattedData = $formatterPlugin->format($routesContext, $formattedData);
        }

        return $formattedData;
    }
}
