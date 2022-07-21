<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MimeTypeTransfer;
use Spryker\Zed\FileManagerGui\Dependency\Facade\FileManagerGuiToFileManagerFacadeInterface;

class MimeTypeFormDataProvider
{
    /**
     * @var \Spryker\Zed\FileManagerGui\Dependency\Facade\FileManagerGuiToFileManagerFacadeInterface
     */
    protected $fileManagerFacade;

    /**
     * @param \Spryker\Zed\FileManagerGui\Dependency\Facade\FileManagerGuiToFileManagerFacadeInterface $fileManagerFacade
     */
    public function __construct(FileManagerGuiToFileManagerFacadeInterface $fileManagerFacade)
    {
        $this->fileManagerFacade = $fileManagerFacade;
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

        $mimeTypeResponseTransfer = $this->fileManagerFacade->findMimeType($idMimeType);

        if ($mimeTypeResponseTransfer->getIsSuccessful()) {
            $mimeTypeTransfer = $mimeTypeResponseTransfer->getMimeTypeOrFail();
        }

        return $mimeTypeTransfer;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions()
    {
        return [];
    }
}
