<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MimeTypeTransfer;
use Orm\Zed\FileManager\Persistence\SpyMimeTypeQuery;

class MimeTypeFormDataProvider
{
    /**
     * @var \Orm\Zed\FileManager\Persistence\SpyMimeTypeQuery
     */
    protected $mimeTypeQuery;

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyMimeTypeQuery $mimeTypeQuery
     */
    public function __construct(SpyMimeTypeQuery $mimeTypeQuery)
    {
        $this->mimeTypeQuery = $mimeTypeQuery;
    }

    /**
     * @param int|null $idMimeType
     *
     * @return \Generated\Shared\Transfer\MimeTypeTransfer
     */
    public function getData(?int $idMimeType = null)
    {
        $mimeTypeTransfer = new MimeTypeTransfer();

        if ($idMimeType === null) {
            return $mimeTypeTransfer;
        }

        $mimeTypeEntity = $this->mimeTypeQuery->findOneByIdMimeType($idMimeType);

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
