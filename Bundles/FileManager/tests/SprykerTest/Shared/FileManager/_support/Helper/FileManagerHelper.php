<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\FileManager\Helper;

use Codeception\Lib\ModuleContainer;
use Codeception\Module;
use Generated\Shared\DataBuilder\FileBuilder;
use Generated\Shared\DataBuilder\FileInfoBuilder;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Zed\FileManager\Business\FileManagerFacadeInterface;
use SprykerTest\Service\Testify\Helper\ServiceHelperTrait;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;
use Symfony\Component\Filesystem\Filesystem;

class FileManagerHelper extends Module
{
    use DataCleanupHelperTrait;
    use BusinessHelperTrait;
    use ConfigHelperTrait;
    use DependencyHelperTrait;
    use ServiceHelperTrait;
    use LocatorHelperTrait;

    /**
     * @var string
     */
    protected const PATH_DOCUMENT = 'documents/';

    /**
     * @var string
     */
    protected const FILE_CONTENT = 'Spryker is awesome';

    /**
     * @var string
     */
    protected const ROOT_DIRECTORY = 'fileSystemRoot/uploads/';

    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @var \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface
     */
    protected FileManagerFacadeInterface $fileManagerFacade;

    /**
     * @param \Codeception\Lib\ModuleContainer $moduleContainer
     * @param array|null $config
     */
    public function __construct(protected ModuleContainer $moduleContainer, ?array $config = null)
    {
        parent::__construct($moduleContainer, $config);
    }

    /**
     * @param array<string, mixed> $seed
     * @param array<string, mixed> $fileInfoSeed
     * @param string $fileSystemName
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    public function haveFile(array $seed = [], array $fileInfoSeed = [], string $fileSystemName = 'files'): FileTransfer
    {
        $fileManagerFacade = $this->getBusinessHelper()->getFacade('FileManager');

        $fileTransfer = (new FileBuilder($seed))->build();
        $fileInfoTransfer = (new FileInfoBuilder($fileInfoSeed))->build();
        $fileInfoTransfer->setStorageName($fileSystemName);

        $fileManagerDataTransfer = new FileManagerDataTransfer();
        $fileManagerDataTransfer->setFileInfo($fileInfoTransfer);
        $fileManagerDataTransfer->setContent(static::FILE_CONTENT);
        $fileManagerDataTransfer->setFile(clone $fileTransfer);
        $createdFileTransfer = $fileManagerFacade->saveFile($fileManagerDataTransfer)->getFile();
        $createdFileTransfer->setFileName($fileTransfer->getFileName());

        $this->getDataCleanupHelper()->_addCleanup(function () use ($createdFileTransfer, $fileManagerFacade): void {
            $fileManagerFacade->deleteFile($createdFileTransfer->getIdFile());
        });

        return $createdFileTransfer;
    }
}
