<?php

namespace Spryker\Service\FileManager;


use Spryker\Service\Kernel\AbstractBundleConfig;
use Spryker\Shared\FileManager\FileManagerConstants;

class FileManagerServiceConfig extends AbstractBundleConfig
{

    /**
     * @return mixed
     */
    public function getStorageName()
    {
        return $this->get(FileManagerConstants::STORAGE_NAME);
    }

}