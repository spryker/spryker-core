<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Form\Validator\Constraints;

use Generated\Shared\Transfer\FileUploadTransfer;
use Spryker\Zed\FileManagerGui\Communication\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\FileValidator as SymfonyFileValidator;

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
     * @param \Generated\Shared\Transfer\FileUploadTransfer|null $fileUploadTransfer
     *
     * @return \Spryker\Zed\FileManagerGui\Communication\File\UploadedFile|null
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
