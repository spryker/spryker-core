<?php


namespace Spryker\Client\FileManager\Model\Adapter;


use Generated\Shared\Transfer\FileReadRequestTransfer;

interface AdapterInterface
{

    /**
     * @param FileWriteRequestTransfer $fileWriteRequestTransfer
     *
     * @return bool
     */
    public function write(FileWriteRequestTransfer $fileWriteRequestTransfer);

    /**
     * @param FileReadRequestTransfer $fileReadRequestTransfer
     *
     * @return mixed
     */
    public function read(FileReadRequestTransfer $fileReadRequestTransfer);

    /**
     * @param FileReadRequestTransfer $fileReadRequestTransfer
     *
     * @return bool
     */
    public function delete(FileReadRequestTransfer $fileReadRequestTransfer);

}
