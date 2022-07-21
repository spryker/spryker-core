<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Dependency\External;

use Spryker\Zed\Oauth\Business\Exception\IOException;
use Symfony\Component\Filesystem\Exception\IOException as SymfonyIOException;
use Symfony\Component\Filesystem\Filesystem;

class OauthToSymfonyFilesystemAdapter implements OauthToFilesystemInterface
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
     * @throws \Spryker\Zed\Oauth\Business\Exception\IOException
     *
     * @return void
     */
    public function dumpFile(string $filename, string $content): void
    {
        try {
            $this->filesystem->dumpFile($filename, $content);
        } catch (SymfonyIOException $e) {
            /** @var \Exception|null $previous */
            $previous = $e->getPrevious();

            throw new IOException($e->getMessage(), $e->getCode(), $previous);
        }
    }
}
