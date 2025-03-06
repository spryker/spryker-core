<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Form\Validator\Constraints;

use Generated\Shared\Transfer\FileUploadTransfer;
use Spryker\Shared\Validator\Constraints\FileValidator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

class FilesValidator extends FileValidator
{
    /**
     * @param mixed $value
     * @param \SprykerFeature\Yves\SspInquiryManagement\Form\Validator\Constraints\Files $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!is_array($value)) {
            $this->context->buildViolation('ssp_inquiry.error.file.format.invalid')
                ->addViolation();

            return;
        }

        $totalMaxSize = $constraint->totalMaxSize;

        if ($totalMaxSize === null) {
            return;
        }

        $totalMaxSize = $this->normalizeBinaryFormat($totalMaxSize);

        $totalSize = 0;
        foreach ($value as $file) {
            $uploadedFile = $this->createSymfonyUploadedFileFromTransfer($file);
            $totalSize += $uploadedFile->getSize();
            parent::validate($uploadedFile, $constraint);
        }

        if ($totalSize <= $totalMaxSize) {
            return;
        }

        $this->context->buildViolation('ssp_inquiry.error.file.size.invalid', [
            '%maxSize%' => $this->convertToReadableSize($totalMaxSize),
            '%size%' => $this->convertToReadableSize($totalSize),
        ])->addViolation();
    }

    /**
     * @param \Generated\Shared\Transfer\FileUploadTransfer $fileUploadTransfer
     *
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    protected function createSymfonyUploadedFileFromTransfer(FileUploadTransfer $fileUploadTransfer): UploadedFile
    {
        return new UploadedFile(
            (string)$fileUploadTransfer->getRealPath(),
            (string)$fileUploadTransfer->getClientOriginalName(),
            $fileUploadTransfer->getMimeTypeName(),
        );
    }

    /**
     * @param int $size
     *
     * @return string
     */
    private function convertToReadableSize(int $size): string
    {
        if ($size >= 1000 * 1000 * 1000) {
            return round($size / (1000 * 1000 * 1000), 2) . ' GB';
        } elseif ($size >= 1000 * 1000) {
            return round($size / (1000 * 1000), 2) . ' MB';
        } elseif ($size >= 1000) {
            return round($size / 1000, 2) . ' kB';
        } else {
            return $size . ' B';
        }
    }

    /**
     * @param string|int $totalMaxSize
     *
     * @throws \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     *
     * @return int
     */
    private function normalizeBinaryFormat(int|string $totalMaxSize): int
    {
        $factors = [
            'k' => 1000,
            'ki' => 1 << 10,
            'm' => 1000 * 1000,
            'mi' => 1 << 20,
            'g' => 1000 * 1000 * 1000,
            'gi' => 1 << 30,
        ];
        if (ctype_digit((string)$totalMaxSize)) {
            $totalMaxSize = (int)$totalMaxSize;
        } elseif (preg_match('/^(\d++)(' . implode('|', array_keys($factors)) . ')$/i', (string)$totalMaxSize, $matches)) {
            $totalMaxSize = (int)$matches[1] * $factors[strtolower($matches[2])];
        } else {
            throw new ConstraintDefinitionException(sprintf('"%s" is not a valid maximum size.', $totalMaxSize));
        }

        return $totalMaxSize;
    }
}
