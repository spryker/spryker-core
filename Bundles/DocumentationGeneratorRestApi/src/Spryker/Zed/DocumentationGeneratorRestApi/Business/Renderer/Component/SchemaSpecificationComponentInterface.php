<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component;

use Generated\Shared\Transfer\OpenApiSpecificationSchemaComponentTransfer;

interface SchemaSpecificationComponentInterface extends SpecificationComponentInterface
{
    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationSchemaComponentTransfer $schemaComponentTransfer
     *
     * @return void
     */
    public function setSchemaComponentTransfer(OpenApiSpecificationSchemaComponentTransfer $schemaComponentTransfer): void;
}
