<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\FileTypeCollectionTransfer;
use Orm\Zed\FileManager\Persistence\Map\SpyFileTypeTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface;

class FileTypeSaver implements FileTypeSaverInterface
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
     * @param \Generated\Shared\Transfer\FileTypeCollectionTransfer $fileTypeCollectionTransfer
     *
     * @return void
     */
    public function updateIsAllowed(FileTypeCollectionTransfer $fileTypeCollectionTransfer)
    {
        list($allowedFileTypes, $disallowedFileTypes) = $this->getFileTypesSeparatedByIsAllowed($fileTypeCollectionTransfer);

        $this->performUpdateIsAllowed($allowedFileTypes, true);
        $this->performUpdateIsAllowed($disallowedFileTypes, false);
    }

    /**
     * @param \Generated\Shared\Transfer\FileTypeCollectionTransfer $fileTypeCollectionTransfer
     *
     * @return array
     */
    protected function getFileTypesSeparatedByIsAllowed(FileTypeCollectionTransfer $fileTypeCollectionTransfer)
    {
        $allowedFileTypes = new ArrayObject();
        $disallowedFileTypes = new ArrayObject();

        foreach ($fileTypeCollectionTransfer->getItems() as $fileTypeTransfer) {
            if ($fileTypeTransfer->getIsAllowed() === true) {
                $allowedFileTypes[] = $fileTypeTransfer;
                continue;
            }

            $disallowedFileTypes[] = $fileTypeTransfer;
        }

        return [$allowedFileTypes, $disallowedFileTypes];
    }

    /**
     * @param \ArrayObject $fileTypes
     * @param bool $isAllowed
     */
    protected function performUpdateIsAllowed(ArrayObject $fileTypes, bool $isAllowed)
    {
        $fileTypeIds = array_map(function ($fileTypeTransfer) {
            return $fileTypeTransfer->getIdFileType();
        }, $fileTypes->getArrayCopy());

        $query = $this->queryContainer->queryFileType()
            ->filterByIsAllowed(!$isAllowed)
            ->where(SpyFileTypeTableMap::COL_ID_FILE_TYPE . Criteria::IN . '?', $fileTypeIds);

        $results = $query->find();

        foreach ($results as $fileType) {
            $fileType->setIsAllowed($isAllowed);
            $fileType->save();
        }
    }
}
