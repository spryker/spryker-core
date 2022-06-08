<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Plugin\DocumentationGeneratorApi;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\SchemaFormatterPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\DocumentationGeneratorOpenApi\DocumentationGeneratorOpenApiFactory getFactory()
 * @method \Spryker\Glue\DocumentationGeneratorOpenApi\DocumentationGeneratorOpenApiConfig getConfig()
 */
class DocumentationGeneratorOpenApiSchemaFormatterPlugin extends AbstractPlugin implements SchemaFormatterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Formats context data for documentation generation.
     *
     * @api
     *
     * @param array<mixed> $formattedData
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return array<mixed>
     */
    public function format(array $formattedData, ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer): array
    {
        return $this->getFactory()
            ->createSchemaFormatterCollection()
            ->format($formattedData, $apiApplicationSchemaContextTransfer);
    }
}
