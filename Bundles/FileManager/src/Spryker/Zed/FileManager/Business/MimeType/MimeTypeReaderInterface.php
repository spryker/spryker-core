<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\MimeType;

interface MimeTypeReaderInterface
{
    /**
     * @param int $idMimeType
     *
     * @return \Generated\Shared\Transfer\MimeTypeResponseTransfer
     */
    public function findMimeType(int $idMimeType);

    /**
     * @return \Generated\Shared\Transfer\MimeTypeCollectionTransfer
     */
    public function findAllowedMimeTypes();
}
