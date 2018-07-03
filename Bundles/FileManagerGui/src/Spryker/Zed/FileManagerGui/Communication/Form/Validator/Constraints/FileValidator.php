<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Form\Validator\Constraints;

use Generated\Shared\Transfer\FileUploadTransfer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\FileValidator as SymfonyFileValidator;

/**
 * @uses \Spryker\Zed\FileManagerGui\Communication\Form\Validator\Constraints\File
 */
class FileValidator extends SymfonyFileValidator
{
    /**
     * @param \Generated\Shared\Transfer\FileUploadTransfer $fileUploadTransfer
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @return void
     */
    public function validate($fileUploadTransfer, Constraint $constraint)
    {
        parent::validate($this->createSymfonyUploadedFileFromTransfer($fileUploadTransfer), $constraint);
    }

    /**
     * @param null|\Generated\Shared\Transfer\FileUploadTransfer $fileUploadTransfer
     *
     * @return null|\Symfony\Component\HttpFoundation\File\UploadedFile
     */
    protected function createSymfonyUploadedFileFromTransfer(?FileUploadTransfer $fileUploadTransfer = null)
    {
        if ($fileUploadTransfer === null) {
            return $fileUploadTransfer;
        }

        return new UploadedFile(
            $fileUploadTransfer->getRealPath(),
            $fileUploadTransfer->getClientOriginalName(),
            $fileUploadTransfer->getMimeTypeName(),
            $fileUploadTransfer->getSize()
        );
    }
}
