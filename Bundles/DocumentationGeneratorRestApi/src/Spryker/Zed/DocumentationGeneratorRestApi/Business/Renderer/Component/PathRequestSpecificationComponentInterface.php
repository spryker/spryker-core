<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component;

use Generated\Shared\Transfer\OpenApiSpecificationPathRequestComponentTransfer;

interface PathRequestSpecificationComponentInterface extends SpecificationComponentInterface
{
    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathRequestComponentTransfer $pathRequestComponentTransfer
     *
     * @return void
     */
    public function setPathRequestComponentTransfer(OpenApiSpecificationPathRequestComponentTransfer $pathRequestComponentTransfer): void;
}
