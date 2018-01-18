<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileManager\Model\Adapter;

use Spryker\Service\FileManager\Dependency\Plugin\FileManagerPluginInterface;

class FileManager
{
    /**
     * @var \Spryker\Service\FileManager\Dependency\Plugin\FileManagerPluginInterface
     */
    protected $fileManagerPlugin;

    /**
     * FileManager constructor.
     *
     * @param \Spryker\Service\FileManager\Dependency\Plugin\FileManagerPluginInterface $fileManagerPlugin
     */
    public function __construct(FileManagerPluginInterface $fileManagerPlugin)
    {
        $this->fileManagerPlugin = $fileManagerPlugin;
    }

    /**
     * @param string $filePath
     *
     * @return string
     */
    public function save(string $filePath)
    {
        return $this->fileManagerPlugin->save($filePath);
    }

    /**
     * @param string $idStorage
     *
     * @return mixed
     */
    public function read(string $idStorage)
    {
        return $this->fileManagerPlugin->read($idStorage);
    }

    /**
     * @param string $idStorage
     *
     * @return bool
     */
    public function delete(string $idStorage)
    {
        return $this->fileManagerPlugin->delete($idStorage);
    }
}
