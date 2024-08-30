<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Dependency\Service;

interface DataImportToFlysystemServiceInterface
{
    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return bool
     */
    public function has(string $filesystemName, string $path): bool;

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return mixed
     */
    public function readStream(string $filesystemName, string $path);
}
