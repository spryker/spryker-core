<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FileManagerStorage;

interface FileManagerStorageClientInterface
{
    /**
     * Specification:
     * - Finds navigation tree in the Key-Value Storage.
     * - Returns the navigation tree with all the stored data if found, NULL otherwise.
     *
     * @api
     *
     * @param int $fileId
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\FileStorageDataTransfer|null
     */
    public function findFileById($fileId, $localeName);
}
