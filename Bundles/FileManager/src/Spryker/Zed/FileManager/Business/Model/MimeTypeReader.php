<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Generated\Shared\Transfer\MimeTypeResponseTransfer;
use Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface;

class MimeTypeReader implements MimeTypeReaderInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface $repository
     */
    public function __construct(FileManagerRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $idMimeType
     *
     * @return \Generated\Shared\Transfer\MimeTypeResponseTransfer
     */
    public function findMimeType(int $idMimeType)
    {
        $mimeTypeResponseTransfer = new MimeTypeResponseTransfer();
        $mimeTypeResponseTransfer->setIsSuccessful(false);
        $mimeTypeTransfer = $this->repository->findMimeType($idMimeType);

        if ($mimeTypeTransfer !== null) {
            $mimeTypeResponseTransfer->setMimeType($mimeTypeTransfer);
            $mimeTypeResponseTransfer->setIsSuccessful(true);
        }

        return $mimeTypeResponseTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\MimeTypeCollectionTransfer
     */
    public function findAllowedMimeTypes()
    {
        return $this->repository->getAllowedMimeTypes();
    }
}
