<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Writer\Csv;

interface CsvWriterInterface
{

    /**
     * @return \SplFileObject
     */
    public function getFile();

    /**
     * @param array $data
     *
     * @return int
     */
    public function write(array $data);

}
