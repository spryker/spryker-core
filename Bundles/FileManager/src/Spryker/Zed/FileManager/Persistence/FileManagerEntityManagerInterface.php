<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Persistence;

use Generated\Shared\Transfer\MimeTypeTransfer;

interface FileManagerEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MimeTypeTransfer $mimeTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MimeTypeTransfer
     */
    public function saveMimeType(MimeTypeTransfer $mimeTypeTransfer);

    /**
     * @param int $idMimeType
     *
     * @return void
     */
    public function deleteMimeType(int $idMimeType);
}
