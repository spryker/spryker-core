<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ReaderManagerInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetParseException
     *
     * @return \Generated\Shared\Transfer\SpyDatasetRowColValueEntityTransfer[]
     */
    public function convertFileToDataTransfers(UploadedFile $file);
}
