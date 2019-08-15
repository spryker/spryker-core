<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\TwigExtension\Dependency\Plugin;

use Spryker\Shared\Twig\Loader\FilesystemLoaderInterface;

interface TwigLoaderPluginInterface
{
    /**
     * Specification:
     * - Returns required twig loader that can be used as separate loader or as part of ChainLoader.
     *
     * @api
     *
     * @return \Spryker\Shared\Twig\Loader\FilesystemLoaderInterface
     */
    public function getLoader(): FilesystemLoaderInterface;
}
