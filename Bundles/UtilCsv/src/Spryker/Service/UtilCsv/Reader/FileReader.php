<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilCsv\Reader;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileReader implements FileReaderInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return array
     */
    public function readFile(UploadedFile $file): array
    {
        $fileObject = $file->openFile();

        $result = [];
        while (!$fileObject->eof()) {
            $result[] = $fileObject->fgetcsv();
        }

        return $result;
    }
}
