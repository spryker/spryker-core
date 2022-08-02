<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Paths;

use Generated\Shared\Transfer\PathMethodComponentDataTransfer;

interface OpenApiSpecificationPathMethodFormatterInterface
{
    /**
     * @param \Generated\Shared\Transfer\PathMethodComponentDataTransfer $pathMethodComponentDataTransfer
     *
     * @return array<mixed>
     */
    public function getPathMethodComponentData(PathMethodComponentDataTransfer $pathMethodComponentDataTransfer): array;

    /**
     * @param string $pattern
     * @param string $resourceType
     *
     * @return string
     */
    public function getDefaultMethodSummary(string $pattern, string $resourceType): string;

    /**
     * @param array<mixed> $pathMethodData
     * @param string $pathName
     * @param string $methodName
     * @param array<mixed> $formattedData
     *
     * @return array<mixed>
     */
    public function addPath(array $pathMethodData, string $pathName, string $methodName, array $formattedData): array;

    /**
     * @param string $resourceType
     *
     * @return string
     */
    public function getPathFromResourceType(string $resourceType): string;
}
