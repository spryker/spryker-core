<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer;

use Generated\Shared\Transfer\RestApiDocumentationSchemaDataTransfer;

interface SchemaRendererInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestApiDocumentationSchemaDataTransfer $schemaDataTransfer
     *
     * @return array
     */
    public function render(RestApiDocumentationSchemaDataTransfer $schemaDataTransfer): array;
}
