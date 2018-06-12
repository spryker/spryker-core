<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Generated\Shared\Transfer\DatasetFilePathTransfer;

interface ReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\DatasetFilePathTransfer $filePathTransfer
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetParseException
     *
     * @return \Generated\Shared\Transfer\SpyDatasetRowColumnValueEntityTransfer[]
     */
    public function parseFileToDataTransfers(DatasetFilePathTransfer $filePathTransfer);
}
