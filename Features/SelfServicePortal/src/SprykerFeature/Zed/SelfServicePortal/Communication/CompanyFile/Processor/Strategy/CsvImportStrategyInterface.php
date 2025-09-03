<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor\Strategy;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface CsvImportStrategyInterface
{
    public function canHandle(string $scope): bool;

    public function extractFileFromForm(FormInterface $form): ?UploadedFile;

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param int $idFile
     *
     * @return array<string, mixed>
     */
    public function processCsvFile(UploadedFile $file, int $idFile): array;
}
