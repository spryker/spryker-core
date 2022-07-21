<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Dependency\External;

use cebe\openapi\spec\OpenApi;
use cebe\openapi\Writer;
use LogicException;
use Spryker\Glue\DocumentationGeneratorOpenApi\Generator\DocumentationContentGeneratorInterface;

class DocumentationGeneratorOpenApiToOpenApiAdapter implements DocumentationContentGeneratorInterface
{
    /**
     * @param array<mixed> $formattedData
     *
     * @throws \LogicException
     *
     * @return string
     */
    public function writeToYaml(array $formattedData): string
    {
        $openApi = new OpenApi($formattedData);

        if (!$openApi->validate()) {
            throw new LogicException(implode(PHP_EOL, $openApi->getErrors()));
        }

        return Writer::writeToYaml($openApi);
    }
}
