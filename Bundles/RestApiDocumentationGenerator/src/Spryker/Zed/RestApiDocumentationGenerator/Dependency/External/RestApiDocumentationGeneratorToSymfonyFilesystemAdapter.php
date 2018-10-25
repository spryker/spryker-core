<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Dependency\External;

use Spryker\Zed\RestApiDocumentationGenerator\Business\Exception\IOException as SprykerIOException;
use Symfony\Component\Filesystem\Exception\IOException;
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
     * @throws \Spryker\Zed\RestApiDocumentationGenerator\Business\Exception\IOException
     *
     * @return void
     */
    public function dumpFile(string $filename, string $content): void
    {
        try {
            $this->filesystem->dumpFile($filename, $content);
        } catch (IOException $e) {
            throw new SprykerIOException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }

    /**
     * @param string|iterable<array,\Traversable> $dirs
     * @param int $mode
     *
     * @throws \Spryker\Zed\RestApiDocumentationGenerator\Business\Exception\IOException
     *
     * @return void
     */
    public function mkdir($dirs, int $mode = self::PERMISSION_ALL): void
    {
        try {
            $this->filesystem->mkdir($dirs, $mode);
        } catch (IOException $e) {
            throw new SprykerIOException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }
}
