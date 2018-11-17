<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer;

use Generated\Shared\Transfer\RestApiDocumentationPathMethodDataTransfer;

interface PathMethodRendererInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer
     *
     * @return array
     */
    public function render(RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer): array;
}
