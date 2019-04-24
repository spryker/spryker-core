<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Loader;

use Twig\Loader\LoaderInterface;

interface FilesystemLoaderInterface extends LoaderInterface
{
    /**
     * @param string $path
     * @param string $namespace
     *
     * @return void
     */
    public function addPath($path, $namespace = '__main__');
}
