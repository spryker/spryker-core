<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Form\Validator\Constraints;

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
        $uploadedFile = new UploadedFile(
            $uploadedFileTransfer->getRealPath(),
            $uploadedFileTransfer->getClientOriginalName(),
            $uploadedFileTransfer->getMimeType(),
            $uploadedFileTransfer->getSize()
        );

        parent::validate($uploadedFile, $constraint);
    }
}
