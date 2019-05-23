<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidget;

use Spryker\Yves\CmsContentWidget\Twig\TwigCmsContentRenderer;
use Spryker\Yves\Kernel\AbstractFactory;

class CmsContentWidgetFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\CmsContentWidget\Twig\TwigCmsContentRendererInterface
     */
    public function createTwigContentRenderer()
    {
        return new TwigCmsContentRenderer($this->getTwigEnvironment());
    }

    /**
     * @return \Spryker\Yves\CmsContentWidget\Dependency\CmsContentWidgetPluginInterface[]
     */
    public function getCmsContentWidgetPlugins()
    {
        return $this->getProvidedDependency(CmsContentWidgetDependencyProvider::CMS_CONTENT_WIDGET_PLUGINS);
    }

    /**
     * @return \Twig\Environment
     */
    protected function getTwigEnvironment()
    {
        return $this->getProvidedDependency(CmsContentWidgetDependencyProvider::TWIG_ENVIRONMENT);
    }
}
