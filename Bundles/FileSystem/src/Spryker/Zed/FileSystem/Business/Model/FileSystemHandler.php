<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Business\Model;

use Spryker\Zed\FileSystem\Dependency\Facade\FileSystemToFlysystemInterface;

class FileSystemHandler implements FileSystemHandlerInterface
{

    /**
     * @var \Spryker\Zed\FileSystem\Dependency\Facade\FileSystemToFlysystemInterface
     */
    protected $flysystemService;

    /**
     * @param \Spryker\Zed\FileSystem\Dependency\Facade\FileSystemToFlysystemInterface $flysystemService
     */
    public function __construct(
        FileSystemToFlysystemInterface $flysystemService
    ) {
        $this->flysystemService = $flysystemService;
    }

    /**
     * @param string $fileSystemName
     * @param string $path
     *
     * @return string|false The file contents or false on failure.
     */
    public function read($fileSystemName, $path)
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemName)
            ->read($path);
    }

}
