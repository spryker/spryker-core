<?php

namespace Spryker\Service\FileManager\Model\Adapter;

use Spryker\Service\FileManager\Dependency\Plugin\FileManagerPluginInterface;

class FileManager
{

    /**
     * @var FileManagerPluginInterface
     */
    protected $fileManagerPlugin;

    /**
     * FileManager constructor.
     *
     * @param FileManagerPluginInterface $fileManagerPlugin
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
     * @param string $contentId
     *
     * @return mixed
     */
    public function read(string $contentId)
    {
        return $this->fileManagerPlugin->read($contentId);
    }

    /**
     * @param string $contentId
     *
     * @return bool
     */
    public function delete(string $contentId)
    {
        return $this->fileManagerPlugin->delete($contentId);
    }

}
