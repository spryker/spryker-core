<?php

namespace Spryker\Service\FileManager;


use Spryker\Service\Kernel\AbstractService;

/**
 * Class FileManagerService
 *
 * @method FileManagerServiceFactory getFactory()
 */
class FileManagerService extends AbstractService
{

    /**
     * @param string $filePath
     *
     * @return string
     * @throws \Spryker\Service\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function save(string $filePath)
    {
        return $this->getFactory()->createFileManagerAdapter()->save($filePath);
    }

    /**
     * @param string $contentId
     *
     * @return mixed
     * @throws \Spryker\Service\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function read(string $contentId)
    {
        return $this->getFactory()->createFileManagerAdapter()->read($contentId);
    }

    /**
     * @param string $contentId
     *
     * @return bool
     * @throws \Spryker\Service\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function delete(string $contentId)
    {
        return $this->getFactory()->createFileManagerAdapter()->delete($contentId);
    }

}