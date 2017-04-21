<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Dependency\Facade;

class FileSystemToFlysystemBridge implements FileSystemToFlysystemInterface
{

    /**
     * @var \Spryker\Service\Flysystem\FlysystemServiceInterface
     */
    protected $flysystemService;

    /**
     * @param \Spryker\Service\Flysystem\FlysystemServiceInterface $flysystemService
     */
    public function __construct($flysystemService)
    {
        $this->flysystemService = $flysystemService;
    }

    /**
     * @param string $name
     *
     * @return \League\Flysystem\Filesystem
     */
    public function getFilesystemByName($name)
    {
        return $this->flysystemService->getFilesystemByName($name);
    }

    /**
     * @return \League\Flysystem\Filesystem[]
     */
    public function getFilesystemCollection()
    {
        return $this->flysystemService->getFilesystemCollection();
    }

}
