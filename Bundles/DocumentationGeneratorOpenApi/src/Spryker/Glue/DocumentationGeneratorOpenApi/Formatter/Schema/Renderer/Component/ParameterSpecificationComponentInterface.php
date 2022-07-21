<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Renderer\Component;

use Generated\Shared\Transfer\ParameterComponentTransfer;

interface ParameterSpecificationComponentInterface
{
    /**
     * @param \Generated\Shared\Transfer\ParameterComponentTransfer $parameterComponentTransfer
     *
     * @return array<mixed>
     */
    public function getSpecificationComponentData(ParameterComponentTransfer $parameterComponentTransfer): array;
}
