<?php


namespace Spryker\Client\FileManager\Model\Proxy;

use Spryker\Client\FileManager\Model\Adapter\AdapterInterface;

class FileManagerProxy
{

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * FileManagerProxy constructor.
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function write(FileWriteRequestTransfer $fileWriteRequestTransfer)
    {
        return $this->adapter->write($fileWriteRequestTransfer);
    }

    /**
     * @param FileReadRequestTransfer $fileReadRequestTransfer
     *
     * @return mixed
     */
    public function read(FileReadRequestTransfer $fileReadRequestTransfer)
    {
        return $this->adapter->read($fileReadRequestTransfer);
    }

    /**
     * @param FileReadRequestTransfer $fileReadRequestTransfer
     *
     * @return bool
     */
    public function delete(FileReadRequestTransfer $fileReadRequestTransfer)
    {
        return $this->adapter->delete($fileReadRequestTransfer);
    }

}
