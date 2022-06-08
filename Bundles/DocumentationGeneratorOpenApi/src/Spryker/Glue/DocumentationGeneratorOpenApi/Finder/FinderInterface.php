<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Finder;

interface FinderInterface
{
    /**
     * @param string $classPath
     *
     * @return array<\SplFileInfo>
     */
    public function getFilesFromClassPath(string $classPath): array;
}
