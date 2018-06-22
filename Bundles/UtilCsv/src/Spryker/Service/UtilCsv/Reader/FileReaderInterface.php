<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilCsv\Reader;

use SplFileObject;

interface FileReaderInterface
{
    /**
     * @param \SplFileObject $file
     *
     * @return array
     */
    public function readFile(SplFileObject $file): array;
}
