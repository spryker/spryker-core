<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component;

use Generated\Shared\Transfer\OpenApiSpecificationPathMethodComponentTransfer;

/**
 * Specification:
 *  - This component describes a single API operation on a path.
 *  - This component covers Operation Object in OpenAPI specification format (see https://swagger.io/specification/#operationObject).
 */
interface PathMethodSpecificationComponentInterface extends SpecificationComponentInterface
{
    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathMethodComponentTransfer $pathMethodComponentTransfer
     *
     * @return void
     */
    public function setPathMethodComponentTransfer(OpenApiSpecificationPathMethodComponentTransfer $pathMethodComponentTransfer): void;
}
