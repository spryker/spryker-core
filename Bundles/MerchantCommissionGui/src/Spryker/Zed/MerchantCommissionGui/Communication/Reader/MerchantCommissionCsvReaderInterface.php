<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionGui\Communication\Reader;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface MerchantCommissionCsvReaderInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     *
     * @return list<\Generated\Shared\Transfer\MerchantCommissionTransfer>
     */
    public function readMerchantCommissionTransfersFromCsvFile(UploadedFile $uploadedFile): array;
}
