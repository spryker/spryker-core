<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilCsv\Reader;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileReaderInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return string[][]
     */
    public function readFile(UploadedFile $file): array;
}
