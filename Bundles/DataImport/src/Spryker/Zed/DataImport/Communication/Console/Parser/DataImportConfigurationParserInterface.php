<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Communication\Console\Parser;

interface DataImportConfigurationParserInterface
{
    /**
     * @param string $filename
     *
     * @return \Generated\Shared\Transfer\DataImportConfigurationTransfer[]
     */
    public function parseConfigurationFile(string $filename): array;
}
