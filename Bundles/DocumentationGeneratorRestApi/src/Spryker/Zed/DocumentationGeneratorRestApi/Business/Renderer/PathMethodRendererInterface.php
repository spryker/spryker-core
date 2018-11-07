<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer;

use Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer;

interface PathMethodRendererInterface
{
    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer
     *
     * @return array
     */
    public function render(OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer): array;
}
