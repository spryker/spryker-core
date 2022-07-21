<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;

/**
 * Provides extension capabilities for formatting documentation by context.
 */
interface SchemaFormatterPluginInterface
{
    /**
     * Specification:
     * - Formats context data for documentation generation.
     *
     * @api
     *
     * @param array<mixed> $formattedData
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return array<mixed>
     */
    public function format(array $formattedData, ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer): array;
}
