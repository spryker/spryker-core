<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Cms;

use Spryker\Yves\Cms\Twig\TwigCmsContentRenderer;
use Spryker\Yves\Kernel\AbstractFactory;

class CmsFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Yves\Cms\Twig\TwigCmsContentRendererInterface
     */
    public function createTwigContentRenderer()
    {
        return new TwigCmsContentRenderer($this->getTwigEnvironment(), $this->getApplicationEnvironment());
    }

    /**
     * @return \Spryker\Yves\Cms\Dependency\CmsContentWidgetPluginInterface[]
     */
    public function getCmsContentWidgetPlugins()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::CMS_CONTENT_WIDGET_PLUGINS);
    }

    /**
     * @return \Twig_Environment
     */
    protected function getTwigEnvironment()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::TWIG_ENVIRONMENT);
    }

    /**
     * @return \Spryker\Shared\Config\Environment
     */
    protected function getApplicationEnvironment()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::APPLICATION_ENVIRONMENT);
    }

}
