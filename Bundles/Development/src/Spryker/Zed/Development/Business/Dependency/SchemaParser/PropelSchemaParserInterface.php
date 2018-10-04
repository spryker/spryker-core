<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\SchemaParser;

use Symfony\Component\Finder\SplFileInfo;

interface PropelSchemaParserInterface
{
    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return string[]
     */
    public function getForeignColumnNames(SplFileInfo $fileInfo): array;

    /**
     * @param string $foreignReferenceColumnName
     * @param string $module
     *
     * @return string
     */
    public function getModuleNameByForeignReference(string $foreignReferenceColumnName, string $module): string;
}
