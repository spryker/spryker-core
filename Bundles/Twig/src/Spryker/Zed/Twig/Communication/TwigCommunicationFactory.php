<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Communication;

use Spryker\Shared\Twig\Cache\Filesystem\PathCache;
use Spryker\Shared\Twig\TwigFileSystem;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Twig\TwigConfig getConfig()
 */
class TwigCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Twig_LoaderInterface
     */
    public function createFilesystemLoader()
    {
        return new TwigFileSystem(
            $this->getConfig()->getTemplatePaths(),
            $this->createPathCache()
        );
    }

    /**
     * @return \Spryker\Shared\Twig\Cache\Filesystem\PathCache
     */
    protected function createPathCache()
    {
        $pathCache = new PathCache(
            $this->getConfig()->getPathCacheFilePath(),
            $this->getConfig()->isPathCacheEnabled()
        );

        return $pathCache;
    }

}
