<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;

class OpenApiSchemaFormatterCollection implements OpenApiSchemaFormatterInterface
{
    /**
     * @var array<\Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\OpenApiSchemaFormatterInterface>
     */
    protected $schemaFormatters;

    /**
     * @param array<\Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\OpenApiSchemaFormatterInterface> $schemaFormatters
     */
    public function __construct(array $schemaFormatters)
    {
        $this->schemaFormatters = $schemaFormatters;
    }

    /**
     * @param array<mixed> $formattedData
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return array<mixed>
     */
    public function format(
        array $formattedData,
        ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
    ): array {
        foreach ($this->schemaFormatters as $schemaFormatter) {
            $formattedData = $schemaFormatter->format($formattedData, $apiApplicationSchemaContextTransfer);
        }

        return $formattedData;
    }
}
