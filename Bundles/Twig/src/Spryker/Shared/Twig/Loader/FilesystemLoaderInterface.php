<?php
// phpcs:ignoreFile

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Loader;

use Twig\Environment;
use Twig\Loader\LoaderInterface;

if (Environment::MAJOR_VERSION < 3) {
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
} else {
    interface FilesystemLoaderInterface extends LoaderInterface
    {
        /**
         * @param string $path
         * @param string $namespace
         *
         * @return void
         */
        public function addPath(string $path, string $namespace = '__main__'): void;
    }
}
