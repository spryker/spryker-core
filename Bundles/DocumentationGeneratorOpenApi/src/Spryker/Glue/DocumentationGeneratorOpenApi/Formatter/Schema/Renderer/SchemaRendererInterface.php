<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Renderer;

use Generated\Shared\Transfer\SchemaDataTransfer;

interface SchemaRendererInterface
{
    /**
     * @param \Generated\Shared\Transfer\SchemaDataTransfer $schemaDataTransfer
     *
     * @return array<mixed>
     */
    public function render(SchemaDataTransfer $schemaDataTransfer): array;
}
