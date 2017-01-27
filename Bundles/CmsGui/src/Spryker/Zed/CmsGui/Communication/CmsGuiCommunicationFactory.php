<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication;

use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsGlossaryFormDataProvider;
use Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsPageFormTypeDataProvider;
use Spryker\Zed\CmsGui\Communication\Form\Glossary\CmsGlossaryAttributesFormType;
use Spryker\Zed\CmsGui\Communication\Form\Glossary\CmsGlossaryFormType;
use Spryker\Zed\CmsGui\Communication\Form\Page\CmsPageAttributesFormType;
use Spryker\Zed\CmsGui\Communication\Form\Page\CmsPageFormType;
use Spryker\Zed\CmsGui\Communication\Form\Page\CmsPageMetaAttributesFormType;
use Spryker\Zed\CmsGui\Communication\Tabs\PageTabs;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToLocaleInterface;
use Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\CmsGui\CmsGuiDependencyProvider;
use Spryker\Zed\CmsGui\Communication\Tabs\GlossaryTabs;

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
     * @param CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Spryker\Zed\CmsGui\Communication\Tabs\GlossaryTabs
     */
    public function createPlaceholderTabs(CmsGlossaryTransfer $cmsGlossaryTransfer)
    {
        return new GlossaryTabs($cmsGlossaryTransfer);
    }

    /**
     * @param \Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsPageFormTypeDataProvider $cmsPageFormTypeDataProvider
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
     * @param \Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsGlossaryFormDataProvider $cmsGlossaryFormDataProvider
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCmsGlossaryForm(CmsGlossaryFormDataProvider $cmsGlossaryFormDataProvider)
    {
        $cmsGlossaryFormType = $this->createCmsGlossaryFormType();

        return $this->getFormFactory()->create(
            $cmsGlossaryFormType,
            $cmsGlossaryFormDataProvider->getData(),
            $cmsGlossaryFormDataProvider->getOptions()
        );
    }

    /**
     * @param array|\Generated\Shared\Transfer\LocaleTransfer[] $availableLocales
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return \Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsPageFormTypeDataProvider
     */
    public function createCmsPageFormTypeDatProvider(array $availableLocales, CmsPageTransfer $cmsPageTransfer = null)
    {
        return new CmsPageFormTypeDataProvider($availableLocales, $this->getCmsQueryContainer(),$cmsPageTransfer);
    }

    /**
     * @return \Spryker\Zed\CmsGui\Communication\Form\Page\CmsPageFormType
     */
    public function createCmsPageFormType()
    {
        return new CmsPageFormType($this->createCmsPageAttributesFormType(), $this->createCmsPageMetaAttributesFormType());
    }

    /**
     * @param array|\Generated\Shared\Transfer\LocaleTransfer[] $availableLocales
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsGlossaryFormDataProvider
     */
    public function createCmsGlossaryFormDataProvider(array $availableLocales, CmsGlossaryTransfer $cmsGlossaryTransfer)
    {
        return new CmsGlossaryFormDataProvider(
            $availableLocales,
            $this->getCmsQueryContainer(),
            $cmsGlossaryTransfer
        );
    }

    /**
     * @return \Spryker\Zed\CmsGui\Communication\Form\Glossary\CmsGlossaryFormType
     */
    public function createCmsGlossaryFormType()
    {
        return new CmsGlossaryFormType($this->createCmsGlossaryAttributesFormType());
    }

    /**
     * @return \Spryker\Zed\CmsGui\Communication\Form\Glossary\CmsGlossaryAttributesFormType
     */
    public function createCmsGlossaryAttributesFormType()
    {
        return new CmsGlossaryAttributesFormType($this->getCmsFacade());
    }

    /**
     * @return \Spryker\Zed\CmsGui\Communication\Form\Page\CmsPageAttributesFormType
     */
    public function createCmsPageAttributesFormType()
    {
        return new CmsPageAttributesFormType();
    }

    /**
     * @return \Spryker\Zed\CmsGui\Communication\Form\Page\CmsPageMetaAttributesFormType
     */
    public function createCmsPageMetaAttributesFormType()
    {
        return new CmsPageMetaAttributesFormType();
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
