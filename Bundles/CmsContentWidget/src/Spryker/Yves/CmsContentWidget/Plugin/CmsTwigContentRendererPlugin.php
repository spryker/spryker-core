<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidget\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\CmsContentWidget\CmsContentWidgetFactory getFactory()
 */
class CmsTwigContentRendererPlugin extends AbstractPlugin implements CmsTwigContentRendererPluginInterface
{

    /**
     * @api
     *
     * @param array $contentList
     * @param array $context
     *
     * @return array
     */
    public function render(array $contentList, array $context)
    {
        return $this->getFactory()
            ->createTwigContentRenderer()
            ->render($contentList, $context);
    }

}
