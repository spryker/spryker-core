<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator;

use Generated\Shared\Transfer\ParameterTransfer;

interface OpenApiSpecificationParameterGeneratorInterface
{
    /**
     * @return array
     */
    public function getParameters(): array;

    /**
     * @param \Generated\Shared\Transfer\ParameterTransfer $parameterTransfer
     *
     * @return void
     */
    public function addParameter(ParameterTransfer $parameterTransfer): void;
}
