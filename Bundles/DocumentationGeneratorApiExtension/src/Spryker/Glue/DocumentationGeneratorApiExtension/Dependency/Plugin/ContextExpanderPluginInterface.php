<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;

/**
 * Provides extension capabilities for expanding documentation context.
 */
interface ContextExpanderPluginInterface
{
    /**
     * Specification:
     * - Adds information to the documentation generation context.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
     */
    public function expand(ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer): ApiApplicationSchemaContextTransfer;
}
