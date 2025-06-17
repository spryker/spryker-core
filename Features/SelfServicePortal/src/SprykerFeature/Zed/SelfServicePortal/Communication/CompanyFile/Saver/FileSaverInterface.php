<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Saver;

interface FileSaverInterface
{
    /**
     * @param array<\Symfony\Component\HttpFoundation\File\UploadedFile> $uploadedFiles
     *
     * @return void
     */
    public function saveFiles(array $uploadedFiles): void;
}
