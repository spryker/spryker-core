<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Dependency\Service;

use League\Csv\Reader;
use League\Csv\Writer;
use SplTempFileObject;

class DatasetToCsvBridge implements DatasetToCsvBridgeInterface
{
    /**
     * @param string $path
     * @param string $mode
     *
     * @return \League\Csv\Reader
     */
    public function createCsvReader($path, $mode): Reader
    {
        return Reader::createFromPath($path, $mode);
    }

    /**
     * @return \League\Csv\Writer
     */
    public function createCsvWriter(): Writer
    {
        return Writer::createFromFileObject(new SplTempFileObject());
    }
}
