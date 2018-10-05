<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Dependency\External;

interface RestApiDocumentationGeneratorToFilesystemInterface
{
    public const PERMISSION_ALL = 0777;

    /**
     * @param string $filename
     * @param string $content
     *
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     *
     * @return void
     */
    public function dumpFile(string $filename, string $content): void;

    /**
     * @param string|iterable<array,\Traversable> $dirs
     * @param int $mode
     *
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     *
     * @return void
     */
    public function mkdir($dirs, int $mode = self::PERMISSION_ALL): void;
}
