<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Plugin\DocumentationGeneratorOpenApi;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Spryker\Glue\DocumentationGeneratorOpenApiExtension\Dependency\Plugin\OpenApiSchemaFormatterPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiFactory getFactory()
 */
class DynamicEntityOpenApiSchemaFormatterPlugin extends AbstractPlugin implements OpenApiSchemaFormatterPluginInterface
{
    /**
     * {@inheritDoc}
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
        return $this->getFactory()->createDynamicApiPathMethodFormatter()->format($apiApplicationSchemaContextTransfer, $formattedData);
    }
}
