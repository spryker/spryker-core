<?php

namespace Spryker\Zed\FileManager\Business;

use Generated\Shared\Transfer\FileManagerSaveRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method FileManagerBusinessFactory getFactory()
 */
class FileManagerFacade extends AbstractFacade
{

    /**
     * @param FileManagerSaveRequestTransfer $saveRequestTransfer
     * @return int
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    public function save(FileManagerSaveRequestTransfer $saveRequestTransfer)
    {
        return $this->getFactory()->createFileSaver()->save($saveRequestTransfer);
    }


    /**
     * @param int $fileId
     * @return bool
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Service\Kernel\Exception\Container\ContainerKeyNotFoundException
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    public function delete(int $fileId)
    {
        return $this->getFactory()->createFileRemover()->delete($fileId);
    }

    /**
     * @param int $fileInfoId
     * @return bool
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Service\Kernel\Exception\Container\ContainerKeyNotFoundException
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    public function deleteFileInfo(int $fileInfoId)
    {
        return $this->getFactory()->createFileRemover()->deleteFileInfo($fileInfoId);
    }

    /**
     * @param int $fileId
     * @param int $fileInfoId
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\FileManager\Exception\FileInfoNotFoundException
     * @throws \Spryker\Zed\FileManager\Exception\FileNotFoundException
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    public function rollback(int $fileId, int $fileInfoId)
    {
        $this->getFactory()->createFileRollback()->rollback($fileId, $fileInfoId);
    }

}
