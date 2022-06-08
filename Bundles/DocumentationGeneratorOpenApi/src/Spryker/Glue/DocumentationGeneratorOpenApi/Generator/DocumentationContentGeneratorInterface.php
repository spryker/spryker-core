<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Generator;

interface DocumentationContentGeneratorInterface
{
    /**
     * @param array<mixed> $formattedData
     *
     * @return string
     */
    public function writeToYaml(array $formattedData): string;
}
