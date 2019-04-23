<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Loader;

use Twig\Loader\FilesystemLoader as TwigFilesystemLoader;

class FilesystemLoader extends TwigFilesystemLoader implements FilesystemLoaderInterface
{
    /**
     * @param string|array $paths A path or an array of paths where to look for templates
     * @param string|null $namespace A path namespace
     * @param string|null $rootPath The root path common to all relative paths (null for getcwd())
     */
    public function __construct($paths = [], ?string $namespace = null, ?string $rootPath = null)
    {
        parent::__construct([], $rootPath);

        if ($paths) {
            $this->setPaths($paths, $namespace ?? self::MAIN_NAMESPACE);
        }
    }
}
