<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\Model\Cleaner;

use Symfony\Component\Filesystem\Filesystem;

class Cleaner implements CleanerInterface
{
    /**
     * @var \Symfony\Component\Filesystem\Filesystem[]
     */
    protected $directories;

    /**
     * @param array $directories
     */
    public function __construct(array $directories)
    {
        $this->directories = $directories;
    }

    /**
     * @return bool
     */
    public function clean()
    {
        $filesystem = new Filesystem();
        foreach ($this->directories as $directory) {
            if (is_dir($directory)) {
                $filesystem->remove($directory);
            }
        }

        return true;
    }
}
