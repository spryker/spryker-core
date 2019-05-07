<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Testify\Business\Cleanup;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class OutputDirectoryCleaner implements OutputDirectoryCleanerInterface
{
    /**
     * @var string[]
     */
    protected $directories;

    /**
     * @param string[] $directories
     */
    public function __construct(array $directories)
    {
        $this->directories = $directories;
    }

    /**
     * @return string[]
     */
    public function cleanup(): array
    {
        $cleanedUpFiles = [];

        $finder = new Finder();
        $finder->in($this->directories)->files();

        $filesystem = new Filesystem();
        foreach ($finder as $splFileInfo) {
            $filesystem->remove($splFileInfo);
            $cleanedUpFiles[] = $splFileInfo->getPathname();
        }

        return $cleanedUpFiles;
    }
}
