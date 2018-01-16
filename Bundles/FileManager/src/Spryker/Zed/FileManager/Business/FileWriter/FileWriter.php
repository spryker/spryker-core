<?php

namespace Spryker\Zed\FileManager\Business\FileWriter;

use Generated\Shared\Transfer\FileSaveRequestTransfer;
use Generated\Shared\Transfer\FileSystemContentTransfer;
use Generated\Shared\Transfer\FileSystemCopyTransfer;
use Generated\Shared\Transfer\FileSystemStreamTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Client\FileManager\FileManagerClient;
use Spryker\Service\FileSystem\FileSystemServiceFactory;
use Spryker\Service\FileSystem\Model\FileSystemStreamInterface;
use Spryker\Service\FileSystem\Model\FileSystemWriterInterface;

class FileWriter
{

    protected $fileManagerClient;

    /**
     * FileSaver constructor.
     */
    public function __construct(FileManagerClient $fileManagerClient)
    {
        $this->fileManagerClient = $fileManagerClient;
    }

    public function write(FileWriteRequestTransfer $fileWriteRequestTransfer)
    {
        $this->fileManagerClient->write($fileWriteRequestTransfer);
    }

}
