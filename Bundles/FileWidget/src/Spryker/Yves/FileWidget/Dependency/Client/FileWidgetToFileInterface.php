<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\FileWidget\Dependency\Client;

interface FileWidgetToFileInterface
{
    /**
     * @param string $fileId
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\FileStorageDataTransfer
     */
    public function findFileById($fileId, $localeName);
}
