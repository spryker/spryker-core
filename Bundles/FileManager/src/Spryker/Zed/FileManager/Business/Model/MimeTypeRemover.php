<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Generated\Shared\Transfer\MimeTypeResponseTransfer;
use Generated\Shared\Transfer\MimeTypeTransfer;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface;

class MimeTypeRemover implements MimeTypeRemoverInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface $queryContainer
     */
    public function __construct(FileManagerQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
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

        $mimeTypeEntity = $this->queryContainer->queryMimeType()->findOneByIdMimeType($mimeTypeTransfer->getIdMimeType());

        if ($mimeTypeEntity !== null) {
            $mimeTypeEntity->delete();
            $mimeTypeResponseTransfer->setIsSuccessful(true);
        }

        return $mimeTypeResponseTransfer;
    }
}
