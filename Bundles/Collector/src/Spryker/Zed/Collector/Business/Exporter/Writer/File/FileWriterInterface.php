<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\File;

use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;

interface FileWriterInterface extends WriterInterface
{

    /**
     * @param string $fileName
     *
     * @return $this
     */
    public function setFileName($fileName);

    /**
     * @return string
     */
    public function getFileName();

    /**
     * @param string $directory
     *
     * @return $this
     */
    public function setDirectory($directory);

    /**
     * @return string
     */
    public function getDirectory();

}
