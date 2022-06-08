<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\CustomRoutesContextTransfer;
use Generated\Shared\Transfer\PathAnnotationTransfer;

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
     * @param array<\Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Processor\PathMethodFormatterInterface> $pathMethodFormatters
     * @param array<\Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Processor\CustomPathMethodFormatterInterface> $customRouteFormatters
     */
    public function __construct(array $pathMethodFormatters, array $customRouteFormatters)
    {
        $this->pathMethodFormatters = $pathMethodFormatters;
        $this->customRouteFormatters = $customRouteFormatters;
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
            $formattedData = $this->applyPathMethodFormatters($formattedData, $resourceContext->getPathAnnotationOrFail());
        }

        foreach ($apiApplicationSchemaContextTransfer->getCustomRoutesContexts() as $customRoutesContext) {
            $formattedData = $this->applyCustomRouteFormatters($formattedData, $customRoutesContext);
        }

        return $formattedData;
    }

    /**
     * @param array<mixed> $formattedData
     * @param \Generated\Shared\Transfer\PathAnnotationTransfer $pathAnnotationTransfer
     *
     * @return array<mixed>
     */
    protected function applyPathMethodFormatters(array $formattedData, PathAnnotationTransfer $pathAnnotationTransfer): array
    {
        foreach ($this->pathMethodFormatters as $formatterPlugin) {
            $formattedData = $formatterPlugin->format($pathAnnotationTransfer, $formattedData);
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
