<?php

namespace Spryker\Zed\FileManager\Business;

use Spryker\Client\FileManager\FileManagerClient;
use Spryker\Zed\FileManager\Business\FileWriter\FileWriter;
use Spryker\Zed\FileManager\FileManagerDependencyProvider;
use Spryker\Zed\Kernel\AbstractFactory;

class FileManagerBusinessFactory extends AbstractFactory
{

    /**
     * @return FileWriter
     */
    public function createFileWriter()
    {
        return new FileWriter($this->getFileManagerAdapter());
    }

    /**
     * @return FileManagerClient
     */
    public function getFileManagerAdapter()
    {
        return $this->getProvidedDependency(FileManagerDependencyProvider::CLIENT_FILE_MANAGER);
    }

}