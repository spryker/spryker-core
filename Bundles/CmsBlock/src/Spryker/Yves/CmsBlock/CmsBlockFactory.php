<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsBlock;

use Spryker\Yves\Kernel\AbstractFactory;

class CmsBlockFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\CmsContentWidget\Plugin\CmsTwigContentRendererPluginInterface
     */
    public function getCmsBlockTwigContentRendererPlugin()
    {
        return $this->getProvidedDependency(CmsBlockDependencyProvider::CMS_BLOCK_TWIG_CONTENT_RENDERER_PLUGIN);
    }
}
