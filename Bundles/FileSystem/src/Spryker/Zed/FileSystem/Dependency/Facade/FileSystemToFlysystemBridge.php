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
     * @param string $filesystem
     * @param string $filename
     *
     * @return string|false The file contents or false on failure.
     */
    public function read($filesystem, $filename)
    {
        return $this->flysystemService
            ->getFilesystemByName($filesystem)
            ->read($filename);
    }

}
