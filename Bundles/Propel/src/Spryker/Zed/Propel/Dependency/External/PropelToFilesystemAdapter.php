<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Dependency\External;

use Symfony\Component\Filesystem\Filesystem;

class PropelToFilesystemAdapter implements PropelToFilesystemAdapterInterface
{
    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    /**
     * @param string|iterable $dirs
     * @param int $mode
     *
     * @return void
     */
    public function mkdir($dirs, $mode = 0777)
    {
        $this->filesystem->mkdir($dirs, $mode);
    }

    /**
     * @param string|iterable $files
     *
     * @return bool
     */
    public function exists($files)
    {
        return $this->filesystem->exists($files);
    }
}
