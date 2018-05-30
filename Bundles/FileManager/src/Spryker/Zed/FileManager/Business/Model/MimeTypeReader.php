<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Generated\Shared\Transfer\MimeTypeCollectionTransfer;
use Generated\Shared\Transfer\MimeTypeTransfer;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface;

class MimeTypeReader implements MimeTypeReaderInterface
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
     * @return \Generated\Shared\Transfer\MimeTypeCollectionTransfer
     */
    public function findAllowedMimeTypes()
    {
        $mimeTypeCollectionTransfer = new MimeTypeCollectionTransfer();
        $mimeTypeCollection = $this->queryContainer
            ->queryMimeType()
            ->filterByIsAllowed(true)
            ->find();

        foreach ($mimeTypeCollection as $mimeTypeEntity) {
            $mimeTypeTransfer = new MimeTypeTransfer();
            $mimeTypeTransfer->fromArray($mimeTypeEntity->toArray());
            $mimeTypeCollectionTransfer->addMimeType($mimeTypeTransfer);
        }

        return $mimeTypeCollectionTransfer;
    }
}
