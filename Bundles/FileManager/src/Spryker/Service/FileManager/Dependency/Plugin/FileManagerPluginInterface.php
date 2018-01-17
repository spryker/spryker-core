<?php

namespace Spryker\Service\FileManager\Dependency\Plugin;

interface FileManagerPluginInterface
{

    /**
     * @param string $filePath
     *
     * @return string
     */
    public function save(string $filePath);

    /**
     * @param string $contentId
     *
     * @return mixed
     */
    public function read(string $contentId);

    /**
     * @param string $contentId
     *
     * @return bool
     */
    public function delete(string $contentId);

}