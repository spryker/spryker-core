<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Form\Validator\Constraints;

use Generated\Shared\Transfer\UploadedFileTransfer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\FileValidator as SymfonyFileValidator;

class FileValidator extends SymfonyFileValidator
{
    /**
     * @param \Generated\Shared\Transfer\UploadedFileTransfer $uploadedFileTransfer
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @return void
     */
    public function validate($uploadedFileTransfer, Constraint $constraint)
    {
        parent::validate($this->createSymfonyUploadedFileFromTransfer($uploadedFileTransfer), $constraint);
    }

    /**
     * @param null|\Generated\Shared\Transfer\UploadedFileTransfer $uploadedFileTransfer
     *
     * @return null|\Symfony\Component\HttpFoundation\File\UploadedFile
     */
    protected function createSymfonyUploadedFileFromTransfer(?UploadedFileTransfer $uploadedFileTransfer = null)
    {
        if ($uploadedFileTransfer === null) {
            return $uploadedFileTransfer;
        }

        return new UploadedFile(
            $uploadedFileTransfer->getRealPath(),
            $uploadedFileTransfer->getClientOriginalName(),
            $uploadedFileTransfer->getMimeType(),
            $uploadedFileTransfer->getSize()
        );
    }
}
