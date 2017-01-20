<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication;

use Spryker\Zed\CmsGui\Communication\Form\CmsPageAttributesFormType;
use Spryker\Zed\CmsGui\Communication\Form\CmsPageFormType;
use Spryker\Zed\CmsGui\Communication\Form\CmsPageMetaAttributesFormType;
use Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsPageFormTypeDataProvider;
use Spryker\Zed\CmsGui\Communication\Tabs\PageTabs;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToLocaleInterface;
use Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\CmsGui\CmsGuiDependencyProvider;
use Spryker\Zed\CmsGui\Communication\Form\CmsGlossaryFormType;

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
     * @param \Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsPageFormTypeDataProvider $cmsPageFormTypeDataProvider
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCmsPageForm(CmsPageFormTypeDataProvider $cmsPageFormTypeDataProvider)
    {
        $cmsPageFormType = $this->createCmsPageFormType();

        return $this->getFormFactory()->create(
            $cmsPageFormType,
            $cmsPageFormTypeDataProvider->getData(),
            $cmsPageFormTypeDataProvider->getOptions()
        );
    }

    /**
     * @param array|\Generated\Shared\Transfer\LocaleTransfer[] $availableLocales
     *
     * @return \Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsPageFormTypeDataProvider
     */
    public function createCmsPageFormTypeDatProvider(array $availableLocales)
    {
        return new CmsPageFormTypeDataProvider($availableLocales, $this->getCmsQueryContainer());
    }

    /**
     * @return \Spryker\Zed\CmsGui\Communication\Form\CmsPageFormType
     */
    public function createCmsPageFormType()
    {
        return new CmsPageFormType($this->createCmsPageAttributesFormType(), $this->createCmsPageMetaAttributesFormType());
    }

    /**
     * @return \Spryker\Zed\CmsGui\Communication\Form\CmsPageAttributesFormType
     */
    public function createCmsPageAttributesFormType()
    {
        return new CmsPageAttributesFormType();
    }

    /**
     * @return \Spryker\Zed\CmsGui\Communication\Form\CmsPageMetaAttributesFormType
     */
    public function createCmsPageMetaAttributesFormType()
    {
        return new CmsPageMetaAttributesFormType();
    }

    /**
     * @return CmsGlossaryFormType
     */
    public function createCmsGlossaryFormType()
    {
        return new CmsGlossaryFormType($this->getCmsFacade());
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

    /**
     * @return CmsGuiToCmsQueryContainerInterface
     */
    public function getCmsQueryContainer()
    {
        return $this->getProvidedDependency(CmsGuiDependencyProvider::QUERY_CONTAINER_CMS);
    }
}
