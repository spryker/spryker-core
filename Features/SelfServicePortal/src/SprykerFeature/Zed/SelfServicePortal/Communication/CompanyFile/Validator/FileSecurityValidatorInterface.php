<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Validator;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileSecurityValidatorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile|null $uploadedFile
     *
     * @return array<string, string|false>
     */
    public function validateUploadedFile(?UploadedFile $uploadedFile): array;

    /**
     * @param string $filePath
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     *
     * @return string
     */
    public function readFileSecurely(string $filePath): string;
}
