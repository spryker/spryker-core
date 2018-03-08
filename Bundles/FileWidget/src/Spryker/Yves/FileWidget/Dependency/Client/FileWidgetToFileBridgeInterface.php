<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\FileWidget\Dependency\Client;

interface FileWidgetToFileBridgeInterface
{
    /**
     * @param string $fileId
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\FileManagerStorageTransfer|null
     */
    public function findFileById($fileId, $localeName);
}
