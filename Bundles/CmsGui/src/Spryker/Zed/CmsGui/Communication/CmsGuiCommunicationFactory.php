<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication;

use Spryker\Zed\CmsGui\Communication\Tabs\PageTabs;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToLocaleInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\CmsGui\CmsGuiDependencyProvider;
use Spryker\Zed\CmsGui\Communication\Form\CmsGlossaryForm;

/**
 * @method \Spryker\Zed\CmsGui\CmsGuiConfig getConfig()
 */
class CmsGuiCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\Gui\Communication\Tabs\TabsInterface
     */
    public function createPageTabs()
    {
        return new PageTabs();
    }

    /**
     * @return CmsGlossaryForm
     */
    public function createCmsGlossaryForm()
    {
        return new CmsGlossaryForm($this->getCmsFacade());
    }

    /**
     * @return CmsGuiToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(CmsGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return CmsGuiToCmsInterface
     */
    public function getCmsFacade()
    {
        return $this->getProvidedDependency(CmsGuiDependencyProvider::FACADE_CMS);
    }
}
