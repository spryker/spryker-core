<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Dependency\Adapter;

use League\Csv\Writer;
use SplFileObject;

class CsvWriterAdapter implements CsvWriterInterface
{
    /**
     * @var \League\Csv\Writer
     */
    protected $writer;

    /**
     * @param \SplFileObject $splFileObject
     */
    public function __construct(SplFileObject $splFileObject)
    {
        $this->writer = Writer::createFromFileObject($splFileObject);
    }

    /**
     * @param array $values
     *
     * @return int
     */
    public function insertOne(array $values): int
    {
        return $this->writer->insertOne($values);
    }

    /**
     * @param array $values
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->writer->getContent();
    }
}
