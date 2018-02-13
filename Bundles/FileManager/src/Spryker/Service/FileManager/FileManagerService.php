<?php

namespace Spryker\Service\FileManager;

use Spryker\Service\Kernel\AbstractService;
use Spryker\Shared\FileManager\FileManagerConstants;

/**
 * @method \Spryker\Service\FileManager\FileManagerServiceFactory getFactory()
 */
class FileManagerService extends AbstractService implements FileManagerServiceInterface
{

    /**
     * @param $fileName
     * @return string
     */
    public function getPublicUrl($fileName)
    {
        return sprintf('/download/%s', $fileName);
    }

    /**
     * @param $fileName
     * @return string
     */
    public function getZedUrl($fileName)
    {
        return sprintf('/file-manager/download?%s=%s', FileManagerConstants::URL_PARAM_ID_FILE_INFO, $fileName);
    }

    /**
     * @param $fileName
     * @return \Generated\Shared\Transfer\FileManagerReadResponseTransfer
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     * @throws \Spryker\Service\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function read($fileName)
    {
        return $this->getFactory()
            ->createFileReader()
            ->read($fileName);
    }

}
