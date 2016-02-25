<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Library\Reader;

interface CsvReaderInterface
{

    /**
     * @param string $filename
     *
     * @return $this
     */
    public function read($filename);

    /**
     * @return array
     */
    public function getColumns();

    /**
     * @return int
     */
    public function getTotal();

    /**
     * @return \SplFileObject
     */
    public function getFile();

    /**
     * @return array
     */
    public function toArray();

}
