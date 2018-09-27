<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Dependency\External;

use Symfony\Component\Filesystem\Filesystem;

class RestApiDocumentationGeneratorToSymfonyFilesystemAdapter implements RestApiDocumentationGeneratorToFilesystemInterface
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
     * @param string $filename
     * @param string $content
     *
     * @return void
     */
    public function dumpFile(string $filename, string $content): void
    {
        $this->filesystem->dumpFile($filename, $content);
    }

    /**
     * @param string|iterable<array,\Traversable> $dirs
     * @param int $mode
     *
     * @return void
     */
    public function mkdir($dirs, int $mode = self::PERMISSION_ALL): void
    {
        $this->filesystem->mkdir($dirs, $mode);
    }
}
