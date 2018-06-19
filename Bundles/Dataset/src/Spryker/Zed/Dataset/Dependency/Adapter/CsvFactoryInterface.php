<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Dependency\Adapter;

use SplFileObject;

interface CsvFactoryInterface
{
    /**
     * @param string $path
     * @param string $mode
     *
     * @return \Spryker\Zed\Dataset\Dependency\Adapter\CsvReaderInterface
     */
    public function createCsvReader(string $path, string $mode): CsvReaderInterface;

    /**
     * @param \SplFileObject $splFileObject
     *
     * @return \Spryker\Zed\Dataset\Dependency\Adapter\CsvWriterInterface
     */
    public function createCsvWriter(SplFileObject $splFileObject): CsvWriterInterface;
}
