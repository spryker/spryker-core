<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\MimeTypeCollectionTransfer;
use Orm\Zed\FileManager\Persistence\Map\SpyMimeTypeTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface;

class MimeTypeSaver implements MimeTypeSaverInterface
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
     * @param \Generated\Shared\Transfer\MimeTypeCollectionTransfer $mimeTypeCollectionTransfer
     *
     * @return void
     */
    public function updateIsAllowed(MimeTypeCollectionTransfer $mimeTypeCollectionTransfer)
    {
        list($allowedMimeTypes, $disallowedMimeTypes) = $this->getMimeTypesSeparatedByIsAllowed($mimeTypeCollectionTransfer);

        $this->performUpdateIsAllowed($allowedMimeTypes, true);
        $this->performUpdateIsAllowed($disallowedMimeTypes, false);
    }

    /**
     * @param \Generated\Shared\Transfer\MimeTypeCollectionTransfer $mimeTypeCollectionTransfer
     *
     * @return array
     */
    protected function getMimeTypesSeparatedByIsAllowed(MimeTypeCollectionTransfer $mimeTypeCollectionTransfer)
    {
        $allowedMimeTypes = new ArrayObject();
        $disallowedMimeTypes = new ArrayObject();

        foreach ($mimeTypeCollectionTransfer->getItems() as $mimeTypeTransfer) {
            if ($mimeTypeTransfer->getIsAllowed() === true) {
                $allowedMimeTypes[] = $mimeTypeTransfer;
                continue;
            }

            $disallowedMimeTypes[] = $mimeTypeTransfer;
        }

        return [$allowedMimeTypes, $disallowedMimeTypes];
    }

    /**
     * @param \ArrayObject $mimeTypes
     * @param bool $isAllowed
     */
    protected function performUpdateIsAllowed(ArrayObject $mimeTypes, bool $isAllowed)
    {
        $mimeTypeIds = array_map(function ($mimeTypeTransfer) {
            return $mimeTypeTransfer->getIdMimeType();
        }, $mimeTypes->getArrayCopy());

        $query = $this->queryContainer->queryMimeType()
            ->filterByIsAllowed(!$isAllowed)
            ->where(SpyMimeTypeTableMap::COL_ID_MIME_TYPE . Criteria::IN . '?', $mimeTypeIds);

        $results = $query->find();

        foreach ($results as $mimeType) {
            $mimeType->setIsAllowed($isAllowed);
            $mimeType->save();
        }
    }
}
