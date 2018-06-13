<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Generated\Shared\Transfer\MimeTypeCollectionTransfer;
use Generated\Shared\Transfer\MimeTypeResponseTransfer;
use Generated\Shared\Transfer\MimeTypeTransfer;
use Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface;
use Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface;

class MimeTypeSaver implements MimeTypeSaverInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface $entityManager
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface $repository
     */
    public function __construct(FileManagerEntityManagerInterface $entityManager, FileManagerRepositoryInterface $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\MimeTypeTransfer $mimeTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MimeTypeResponseTransfer
     */
    public function saveMimeType(MimeTypeTransfer $mimeTypeTransfer)
    {
        $mimeTypeResponseTransfer = new MimeTypeResponseTransfer();
        $mimeTypeResponseTransfer->setMimeType($mimeTypeTransfer);
        $mimeTypeResponseTransfer->setIsSuccessful(false);

        if (!$this->validateMimeType($mimeTypeTransfer)) {
            return $mimeTypeResponseTransfer;
        }

        $this->entityManager->saveMimeType($mimeTypeTransfer);
        $mimeTypeResponseTransfer->setIsSuccessful(true);

        return $mimeTypeResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MimeTypeCollectionTransfer $mimeTypeCollectionTransfer
     *
     * @return void
     */
    public function updateIsAllowed(MimeTypeCollectionTransfer $mimeTypeCollectionTransfer)
    {
        foreach ($mimeTypeCollectionTransfer->getItems() as $mimeTypeTransfer) {
            $this->entityManager->saveMimeType($mimeTypeTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MimeTypeTransfer $mimeTypeTransfer
     *
     * @return bool
     */
    protected function validateMimeType(MimeTypeTransfer $mimeTypeTransfer)
    {
        return $this->repository->getMimeTypeByIdMimeTypeAndName($mimeTypeTransfer) === null;
    }
}
