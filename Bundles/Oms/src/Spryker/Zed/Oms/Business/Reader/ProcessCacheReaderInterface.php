<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Reader;

use Spryker\Zed\Oms\Business\Process\ProcessInterface;

interface ProcessCacheReaderInterface
{
    /**
     * @param string $processName
     *
     * @return bool
     */
    public function hasProcess(string $processName): bool;

    /**
     * @param string $processName
     *
     * @return \Spryker\Zed\Oms\Business\Process\ProcessInterface
     */
    public function getProcess(string $processName): ProcessInterface;

    /**
     * @param string $processName
     *
     * @return string
     */
    public function getFullFilename(string $processName): string;
}
