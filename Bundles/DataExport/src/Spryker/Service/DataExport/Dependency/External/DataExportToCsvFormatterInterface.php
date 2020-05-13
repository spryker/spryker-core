<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport\Dependency\External;

interface DataExportToCsvFormatterInterface
{
    /**
     * @param array $record
     *
     * @return int
     */
    public function addRecord(array $record): int;

    /**
     * @return string
     */
    public function getFormattedRecords(): string;
}
