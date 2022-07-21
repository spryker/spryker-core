<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Dependency\External;

use Spryker\Glue\GlueApplication\Exception\IOException;
use Symfony\Component\Filesystem\Exception\IOException as SymfonyIOException;
use Symfony\Component\Filesystem\Filesystem;

class GlueApplicationToSymfonyFilesystemAdapter implements GlueApplicationToSymfonyFilesystemInterface
{
    /**
     * @param string $filename
     * @param string $content
     *
     * @throws \Spryker\Glue\GlueApplication\Exception\IOException
     *
     * @return void
     */
    public function dumpFile(string $filename, string $content): void
    {
        try {
            (new Filesystem())->dumpFile($filename, $content);
        } catch (SymfonyIOException $e) {
            throw new IOException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }
}
