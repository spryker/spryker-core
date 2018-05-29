<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\MimeTypeCollectionTransfer;
use Generated\Shared\Transfer\MimeTypeResponseTransfer;
use Generated\Shared\Transfer\MimeTypeTransfer;
use Orm\Zed\FileManager\Persistence\Map\SpyMimeTypeTableMap;
use Orm\Zed\FileManager\Persistence\SpyMimeType;
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

        $mimeTypeEntity = new SpyMimeType();

        if ($mimeTypeTransfer->getIdMimeType()) {
            $mimeTypeEntity = $this->queryContainer->queryMimeType()->findOneByIdMimeType($mimeTypeTransfer->getIdMimeType());
        }

        $mimeTypeEntity->fromArray($mimeTypeTransfer->toArray());
        $mimeTypeEntity->save();

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
        list($allowedMimeTypes, $disallowedMimeTypes) = $this->getMimeTypesSeparatedByIsAllowed($mimeTypeCollectionTransfer);

        $this->performUpdateIsAllowed($allowedMimeTypes, true);
        $this->performUpdateIsAllowed($disallowedMimeTypes, false);
    }

    /**
     * @param \Generated\Shared\Transfer\MimeTypeTransfer $mimeTypeTransfer
     *
     * @return bool
     */
    protected function validateMimeType(MimeTypeTransfer $mimeTypeTransfer)
    {
        $query = $this->queryContainer->queryMimeType();

        if ($mimeTypeTransfer->getIdMimeType() !== null) {
            $query->filterByIdMimeType($mimeTypeTransfer->getIdMimeType(), Criteria::NOT_EQUAL);
        }

        $mimeTypeEntity = $query->findOneByName($mimeTypeTransfer->getName());

        return $mimeTypeEntity === null;
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
     *
     * @return void
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
