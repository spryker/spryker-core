<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Reader\Csv;

interface CsvReaderInterface
{

    /**
     * @param array $data
     *
     * @return array
     */
    public function composeItem(array $data);

    /**
     * @return array
     */
    public function getColumns();

    /**
     * @return \SplFileObject
     */
    public function getFile();

    /**
     * @return int
     */
    public function getTotal();

    /**
     * @param string $filename
     *
     * @return $this
     */
    public function load($filename);

    /**
     * @return array
     */
    public function read();

    /**
     * @return array
     */
    public function toArray();

}
