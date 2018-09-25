<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer;

use Generated\Shared\Transfer\RestApiDocumentationSecuritySchemeTransfer;

interface SecuritySchemeRendererInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestApiDocumentationSecuritySchemeTransfer $securitySchemeTransfer
     *
     * @return array
     */
    public function render(RestApiDocumentationSecuritySchemeTransfer $securitySchemeTransfer): array;
}
