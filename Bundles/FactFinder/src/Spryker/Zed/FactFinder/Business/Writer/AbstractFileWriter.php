<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Writer;

abstract class AbstractFileWriter
{

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @param string $filePath
     * @param array $data
     * @param bool $append
     *
     * @return void
     */
    abstract public function write($filePath, $data, $append = false);

    /**
     * @param string $fileName
     *
     * @return void
     */
    protected function createDirectory($fileName)
    {
        $pathInfo = pathinfo($fileName);
        $directory = $pathInfo['dirname'];

        if (!file_exists($directory)) {
            mkdir($directory);
        }
    }

}
