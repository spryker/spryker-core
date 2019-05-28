<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Dependency\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface PriceProductScheduleGuiToUtilCsvServiceInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return array
     */
    public function readUploadedFile(UploadedFile $file): array;
}
