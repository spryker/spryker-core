<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Twig;

use Spryker\Shared\Twig\Cache\Filesystem\FilesystemLoaderCache;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Twig\Model\Loader\FilesystemLoader;

/**
 * @method \Spryker\Yves\Twig\TwigConfig getConfig()
 */
class TwigFactory extends AbstractFactory
{

    /**
     * @return \Twig_LoaderInterface
     */
    public function createFilesystemLoader()
    {
        return new FilesystemLoader(
            $this->getConfig()->getTemplatePaths(),
            $this->createFilesystemLoaderCache(),
            $this->getUtilTextService()
        );
    }

    /**
     * @return \Spryker\Shared\Twig\Cache\Filesystem\FilesystemLoaderCache
     */
    protected function createFilesystemLoaderCache()
    {
        $filesystemLoaderCache = new FilesystemLoaderCache(
            $this->getConfig()->getPathCacheFilePath(),
            $this->getConfig()->isPathCacheEnabled()
        );

        return $filesystemLoaderCache;
    }

    /**
     * @return \Spryker\Shared\Twig\Dependency\Service\TwigToUtilTextServiceInterface
     */
    protected function getUtilTextService()
    {
        return $this->getProvidedDependency(TwigDependencyProvider::SERVICE_UTIL_TEXT);
    }

}
