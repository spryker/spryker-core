<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Library\Import;

/**
 * @deprecated Use \Spryker\Zed\Library\Reader\CsvReaderInterface read() instead.
 *
 * @see \Spryker\Zed\Library\Reader\CsvReaderInterface
 */
interface ReaderInterface
{

    /**
     * @deprecated Use \Spryker\Zed\Library\Reader\CsvReaderInterface's read() instead.
     *
     * @see \Spryker\Zed\Library\Reader\CsvReaderInterface::read
     *
     * @param mixed $inputData
     *
     * @return \Spryker\Zed\Library\Import\Input
     */
    public function read($inputData);

}
