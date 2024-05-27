<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionGui\Communication\Validator;

use ArrayObject;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface MerchantCommissionCsvValidatorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer>
     */
    public function validateMerchantCommissionCsvFile(UploadedFile $uploadedFile): ArrayObject;
}
