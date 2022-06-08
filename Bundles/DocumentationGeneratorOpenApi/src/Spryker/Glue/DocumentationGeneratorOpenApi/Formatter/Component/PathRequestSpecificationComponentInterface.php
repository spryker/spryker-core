<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Component;

interface PathRequestSpecificationComponentInterface
{
    /**
     * @param array<mixed> $pathMethodData
     * @param string $requestSchemaName
     *
     * @return array<mixed>
     */
    public function getSpecificationComponentData(array $pathMethodData, string $requestSchemaName): array;
}
