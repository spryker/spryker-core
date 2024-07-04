<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Writer;

use Spryker\Zed\Oms\Business\Process\ProcessInterface;
use Spryker\Zed\Oms\Business\Reader\ProcessCacheReaderInterface;
use Spryker\Zed\Oms\OmsConfig;

class ProcessCacheWriter implements ProcessCacheWriterInterface
{
    /**
     * @var \Spryker\Zed\Oms\OmsConfig
     */
    protected OmsConfig $omsConfig;

    /**
     * @var \Spryker\Zed\Oms\Business\Reader\ProcessCacheReaderInterface
     */
    protected ProcessCacheReaderInterface $processCacheReader;

    /**
     * @param \Spryker\Zed\Oms\OmsConfig $omsConfig
     * @param \Spryker\Zed\Oms\Business\Reader\ProcessCacheReaderInterface $processCacheReader
     */
    public function __construct(
        OmsConfig $omsConfig,
        ProcessCacheReaderInterface $processCacheReader
    ) {
        $this->omsConfig = $omsConfig;
        $this->processCacheReader = $processCacheReader;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     * @param string|null $processName
     *
     * @return string
     */
    public function cacheProcess(ProcessInterface $process, ?string $processName = null): string
    {
        $this->createCacheDirectory();

        if (!$processName) {
            $processName = $process->getName();
        }
        $fullFilename = $this->processCacheReader->getFullFilename($processName);

        file_put_contents($fullFilename, serialize($process));

        return $fullFilename;
    }

    /**
     * @return void
     */
    protected function createCacheDirectory(): void
    {
        if (file_exists($this->omsConfig->getProcessCachePath())) {
            return;
        }

        mkdir($this->omsConfig->getProcessCachePath(), $this->omsConfig->getOmsProcessCacheFilePermission(), true);
    }
}
