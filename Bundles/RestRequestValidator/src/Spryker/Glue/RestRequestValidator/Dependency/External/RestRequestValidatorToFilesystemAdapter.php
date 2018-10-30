<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestRequestValidator\Dependency\External;

use Symfony\Component\Filesystem\Filesystem;

class RestRequestValidatorToFilesystemAdapter implements RestRequestValidatorToFilesystemAdapterInterface
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
     * @param string $file
     *
     * @return bool
     */
    public function exists(string $file): bool
    {
        return $this->filesystem->exists($file);
    }
}
