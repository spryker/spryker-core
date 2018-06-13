<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Generated\Shared\Transfer\MimeTypeResponseTransfer;
use Generated\Shared\Transfer\MimeTypeTransfer;
use Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface;

class MimeTypeRemover implements MimeTypeRemoverInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface $entityManager
     */
    public function __construct(FileManagerEntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\MimeTypeTransfer $mimeTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MimeTypeResponseTransfer
     */
    public function deleteMimeType(MimeTypeTransfer $mimeTypeTransfer)
    {
        $mimeTypeResponseTransfer = new MimeTypeResponseTransfer();
        $mimeTypeResponseTransfer->setIsSuccessful(false);

        if ($mimeTypeTransfer->getIdMimeType() === null) {
            return $mimeTypeResponseTransfer;
        }

        $this->entityManager->deleteMimeType($mimeTypeTransfer->getIdMimeType());
        $mimeTypeResponseTransfer->setIsSuccessful(true);

        return $mimeTypeResponseTransfer;
    }
}
