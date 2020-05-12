<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport\Dependency\External;

use League\Csv\Writer;

class DataExportToLeagueCsvWriterAdapter implements DataExportToCsvWriterInterface
{
    /**
     * @var \League\Csv\Writer
     */
    protected $writer;

    public function __construct()
    {
        $this->writer = Writer::createFromString();
    }

    /**
     * @param array $record
     *
     * @return int
     */
    public function insertOne(array $record): int
    {
        return $this->writer->insertOne($record);
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->writer->getContent();
    }
}
