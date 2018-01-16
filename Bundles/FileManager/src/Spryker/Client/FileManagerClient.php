<?php


namespace Spryker\Client\FileManager;


use Spryker\Client\Kernel\AbstractClient;


/**
 * @method FileManagerFactory getFactory()
 */
class FileManagerClient extends AbstractClient
{

    public function write(FileWriteRequestTransfer $fileWriteRequestTransfer)
    {
        return $this->getFactory()->createFileManagerProxy()->write($fileWriteRequestTransfer);
    }

    /**
     * @param FileReadRequestTransfer $fileReadRequestTransfer
     *
     * @return mixed
     */
    public function read(FileReadRequestTransfer $fileReadRequestTransfer)
    {
        return $this->getFactory()->createFileManagerProxy()->read($fileReadRequestTransfer);
    }

    /**
     * @param FileReadRequestTransfer $fileReadRequestTransfer
     *
     * @return bool
     */
    public function delete(FileReadRequestTransfer $fileReadRequestTransfer)
    {
        return $this->getFactory()->createFileManagerProxy()->delete($fileReadRequestTransfer);
    }

}
