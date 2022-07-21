<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Renderer\Component;

use Generated\Shared\Transfer\SchemaItemsComponentTransfer;

interface SchemaItemsSpecificationComponentInterface
{
    /**
     * @param \Generated\Shared\Transfer\SchemaItemsComponentTransfer $schemaItemsComponentTransfer
     *
     * @return array<mixed>
     */
    public function getSpecificationComponentData(SchemaItemsComponentTransfer $schemaItemsComponentTransfer): array;
}
