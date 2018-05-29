<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MimeTypeTransfer;
use Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerInterface;

class MimeTypeFormDataProvider
{
    /**
     * @var \Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerInterface $queryContainer
     */
    public function __construct(FileManagerGuiToFileManagerQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int|null $idMimeType
     *
     * @return \Generated\Shared\Transfer\MimeTypeTransfer
     */
    public function getData(int $idMimeType = null)
    {
        $mimeTypeTransfer = new MimeTypeTransfer();

        if ($idMimeType === null) {
            return $mimeTypeTransfer;
        }

        $mimeTypeEntity = $this->queryContainer->queryMimeType()->findOneByIdMimeType($idMimeType);

        if ($mimeTypeEntity !== null) {
            $mimeTypeTransfer->fromArray($mimeTypeEntity->toArray());
        }

        return $mimeTypeTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }
}
