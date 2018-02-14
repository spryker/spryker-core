<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FileManagerStorage\Storage;

interface FileStorageInterface
{
    /**
     * @param int $fileId
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\FileStorageTransfer
     */
    public function findFileById($fileId, $localeName);
}
