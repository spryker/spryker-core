<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Loader;

use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigLoaderPluginInterface;
use Twig\Loader\ChainLoader;

class TwigChainLoader extends ChainLoader implements TwigChainLoaderInterface
{
    /**
     * @param \Spryker\Shared\TwigExtension\Dependency\Plugin\TwigLoaderPluginInterface[] $loaderPlugins
     */
    public function __construct(array $loaderPlugins = [])
    {
        parent::__construct($this->mapLoaders($loaderPlugins));
    }

    /**
     * @param \Spryker\Shared\TwigExtension\Dependency\Plugin\TwigLoaderPluginInterface[] $loaderPlugins
     *
     * @return \Spryker\Shared\Twig\Loader\FilesystemLoaderInterface[]
     */
    protected function mapLoaders(array $loaderPlugins = []): array
    {
        return array_map(function (TwigLoaderPluginInterface $loaderPlugin) {
            return $loaderPlugin->getLoader();
        }, $loaderPlugins);
    }
}
