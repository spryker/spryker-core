<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsBlock;

use RuntimeException;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class CmsBlockDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CMS_BLOCK_TWIG_CONTENT_RENDERER_PLUGIN = 'cms twig content renderer plugin';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container[static::CMS_BLOCK_TWIG_CONTENT_RENDERER_PLUGIN] = function (Container $container) {
            return $this->getCmsBlockTwigContentRendererPlugin();
        };

        return $container;
    }

    /**
     * @throws \RuntimeException
     *
     * @return \Spryker\Yves\CmsContentWidget\Plugin\CmsTwigContentRendererPluginInterface
     */
    protected function getCmsBlockTwigContentRendererPlugin()
    {
        throw new RuntimeException('Implement getCmsBlockTwigContentRendererPlugin().');
    }
}
