<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilCsv\Reader;

use SplFileObject;

class FileReader implements FileReaderInterface
{
    /**
     * @param \SplFileObject $file
     *
     * @return array
     */
    public function readFile(SplFileObject $file): array
    {
        $file->setFlags(SplFileObject::READ_CSV);

        $result = [];
        foreach ($file as $row) {
            $result[] = $row;
        }
        return $result;
    }
}
