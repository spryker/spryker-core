<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Dependency\Service;

interface DatasetToCsvBridgeInterface
{
    /**
     * @param string $path
     * @param string $mode
     *
     * @return \League\Csv\Reader
     */
    public function createCsvReader($path, $mode);

    /**
     * @return \League\Csv\Writer
     */
    public function createCsvWriter();
}
