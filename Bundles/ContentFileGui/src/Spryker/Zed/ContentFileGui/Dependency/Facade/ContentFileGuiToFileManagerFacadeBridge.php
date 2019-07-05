<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFileGui\Dependency\Facade;

class ContentFileGuiToFileManagerFacadeBridge implements ContentFileGuiToFileManagerFacadeInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface
     */
    protected $fileManagerFacade;

    /**
     * @param \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface $fileManagerFacade
     */
    public function __construct($fileManagerFacade)
    {
        $this->fileManagerFacade = $fileManagerFacade;
    }

    /**
     * @param int[] $idFiles
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer[]
     */
    public function getFilesByIds(array $idFiles): array
    {
        return $this->fileManagerFacade->getFilesByIds($idFiles);
    }
}
