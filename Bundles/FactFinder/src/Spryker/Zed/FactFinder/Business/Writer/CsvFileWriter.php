<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Writer;

class CsvFileWriter extends AbstractFileWriter
{

    const DELIMITER = ',';

    /**
     * @param string $filePath
     * @param array $data
     * @param bool $append
     *
     * @return void
     */
    public function write($filePath, $data, $append = false)
    {
        $this->createDirectory($filePath);

        if ($append) {
            $filePointer = fopen($filePath, 'a');
        } else {
            $filePointer = fopen($filePath, 'w');
        }

        foreach ($data as $row) {
            fputcsv($filePointer, $row, self::DELIMITER);
        }

        fclose($filePointer);
    }

}
