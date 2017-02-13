<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication;

use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Spryker\Zed\CmsGui\CmsGuiDependencyProvider;
use Spryker\Zed\CmsGui\Communication\Autocomplete\AutocompleteDataProvider;
use Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueGlossaryForSearchType;
use Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueName;
use Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueUrl;
use Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsGlossaryFormTypeDataProvider;
use Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsPageFormTypeDataProvider;
use Spryker\Zed\CmsGui\Communication\Form\Glossary\CmsGlossaryAttributesFormType;
use Spryker\Zed\CmsGui\Communication\Form\Glossary\CmsGlossaryFormType;
use Spryker\Zed\CmsGui\Communication\Form\Page\CmsPageAttributesFormType;
use Spryker\Zed\CmsGui\Communication\Form\Page\CmsPageFormType;
use Spryker\Zed\CmsGui\Communication\Form\Page\CmsPageMetaAttributesFormType;
use Spryker\Zed\CmsGui\Communication\Table\CmsPageTable;
use Spryker\Zed\CmsGui\Communication\Tabs\GlossaryTabs;
use Spryker\Zed\CmsGui\Communication\Tabs\PageTabs;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CmsGui\CmsGuiConfig getConfig()
 */
class CmsGuiCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\CmsGui\Communication\Table\CmsPageTable
     */
    public function createCmsPageTable()
    {
        return new CmsPageTable(
            $this->getCmsQueryContainer(),
            $this->getLocaleFacade(),
            $this->getConfig(),
            $this->getCmsFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Tabs\TabsInterface
     */
    public function createPageTabs()
    {
        return new PageTabs();
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Spryker\Zed\CmsGui\Communication\Tabs\GlossaryTabs
     */
    public function createPlaceholderTabs(CmsGlossaryTransfer $cmsGlossaryTransfer)
    {
        return new GlossaryTabs($cmsGlossaryTransfer);
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
     * @param \Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsGlossaryFormTypeDataProvider $cmsGlossaryFormTypeDataProvider
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCmsGlossaryForm(CmsGlossaryFormTypeDataProvider $cmsGlossaryFormTypeDataProvider)
    {
        $cmsGlossaryFormType = $this->createCmsGlossaryFormType();

        return $this->getFormFactory()->create(
            $cmsGlossaryFormType,
            $cmsGlossaryFormTypeDataProvider->getData(),
            $cmsGlossaryFormTypeDataProvider->getOptions()
        );
    }

    /**
     * @param array|\Generated\Shared\Transfer\LocaleTransfer[] $availableLocales
     * @param \Generated\Shared\Transfer\CmsPageTransfer|null $cmsPageTransfer
     *
     * @return \Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsPageFormTypeDataProvider
     */
    public function createCmsPageFormTypeDataProvider(array $availableLocales, CmsPageTransfer $cmsPageTransfer = null)
    {
        return new CmsPageFormTypeDataProvider(
            $availableLocales,
            $this->getCmsQueryContainer(),
            $this->getCmsFacade(),
            $cmsPageTransfer
        );
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createCmsPageFormType()
    {
        return new CmsPageFormType($this->createCmsPageAttributesFormType(), $this->createCmsPageMetaAttributesFormType());
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsGlossaryFormTypeDataProvider
     */
    public function createCmsGlossaryFormTypeDataProvider(CmsGlossaryTransfer $cmsGlossaryTransfer)
    {
        return new CmsGlossaryFormTypeDataProvider($cmsGlossaryTransfer);
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createCmsGlossaryFormType()
    {
        return new CmsGlossaryFormType($this->createCmsGlossaryAttributesFormType());
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createCmsGlossaryAttributesFormType()
    {
        return new CmsGlossaryAttributesFormType(
            $this->getCmsFacade(),
            $this->createUniqueGlossaryForSearchTypeConstraint()
        );
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createCmsPageAttributesFormType()
    {
        return new CmsPageAttributesFormType($this->createUniqueUrlConstraint(), $this->createUniqueNameConstraint());
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createCmsPageMetaAttributesFormType()
    {
        return new CmsPageMetaAttributesFormType();
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createUniqueUrlConstraint()
    {
        return new UniqueUrl([
            UniqueUrl::OPTION_URL_FACADE => $this->getUrlFacade(),
            UniqueUrl::OPTION_CMS_FACADE => $this->getCmsFacade(),
        ]);
    }

    /**
     * @return \Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueName
     */
    protected function createUniqueNameConstraint()
    {
        return new UniqueName([
            UniqueName::OPTION_CMS_QUERY_CONTAINER => $this->getCmsQueryContainer(),
        ]);
    }

    /**
     * @return \Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueGlossaryForSearchType
     */
    protected function createUniqueGlossaryForSearchTypeConstraint()
    {
        return new UniqueGlossaryForSearchType([
            UniqueGlossaryForSearchType::OPTION_GLOSSARY_FACADE => $this->getGlossaryFacade(),
        ]);
    }

    /**
     * @return \Spryker\Zed\CmsGui\Communication\Autocomplete\AutocompleteDataProviderInterface
     */
    public function createAutocompleteDataProvider()
    {
        return new AutocompleteDataProvider($this->getCmsQueryContainer());
    }

    /**
     * @return \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(CmsGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface
     */
    public function getCmsFacade()
    {
        return $this->getProvidedDependency(CmsGuiDependencyProvider::FACADE_CMS);
    }

    /**
     * @return \Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface
     */
    public function getCmsQueryContainer()
    {
        return $this->getProvidedDependency(CmsGuiDependencyProvider::QUERY_CONTAINER_CMS);
    }

    /**
     * @return \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToUrlInterface
     */
    public function getUrlFacade()
    {
        return $this->getProvidedDependency(CmsGuiDependencyProvider::FACADE_URL);
    }

    /**
     * @return \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsGlossaryFacadeInterface
     */
    public function getGlossaryFacade()
    {
        return $this->getProvidedDependency(CmsGuiDependencyProvider::FACADE_GLOSSARY);
    }

}
