<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Persistence;

use Generated\Shared\Transfer\MimeTypeTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\FileManager\Persistence\FileManagerPersistenceFactory getFactory()
 */
class FileManagerEntityManager extends AbstractEntityManager implements FileManagerEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MimeTypeTransfer $mimeTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MimeTypeTransfer
     */
    public function saveMimeType(MimeTypeTransfer $mimeTypeTransfer)
    {
        $mimeTypeEntity = $this->getFactory()
            ->createMimeTypeQuery()
            ->filterByIdMimeType($mimeTypeTransfer->getIdMimeType())
            ->findOneOrCreate();

        $mimeTypeEntity = $this->getFactory()
            ->createFileManagerMapper()
            ->mapMimeTypeTransferToEntity($mimeTypeTransfer, $mimeTypeEntity);

        $mimeTypeTransfer->setIdMimeType($mimeTypeEntity->getIdMimeType());
        $mimeTypeEntity->save();

        return $mimeTypeTransfer;
    }

    /**
     * @param int $idMimeType
     *
     * @return void
     */
    public function deleteMimeType(int $idMimeType)
    {
        $this->getFactory()
            ->createMimeTypeQuery()
            ->filterByIdMimeType($idMimeType)
            ->delete();
    }
}
