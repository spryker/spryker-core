<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Renderer\Component;

use Generated\Shared\Transfer\ParameterComponentTransfer;

/**
 * Specification:
 *  - This component describes a single Object.
 *  - This component covers Parameter Object in OpenAPI specification format (see https://swagger.io/specification/#componentsObject).
 */
class ParameterSpecificationComponent implements ParameterSpecificationComponentInterface
{
    /**
     * @param \Generated\Shared\Transfer\ParameterComponentTransfer $parameterComponentTransfer
     *
     * @return array<mixed>
     */
    public function getSpecificationComponentData(ParameterComponentTransfer $parameterComponentTransfer): array
    {
        return [
            $parameterComponentTransfer->getRefName() => [
                ParameterComponentTransfer::NAME => $parameterComponentTransfer->getNameOrFail(),
                ParameterComponentTransfer::IN => $parameterComponentTransfer->getInOrFail(),
                ParameterComponentTransfer::DESCRIPTION => $parameterComponentTransfer->getDescription(),
                ParameterComponentTransfer::SCHEMA => $parameterComponentTransfer->getSchemaOrFail()->toArray(),
                ParameterComponentTransfer::REQUIRED => $parameterComponentTransfer->getRequired(),
            ],
        ];
    }
}
