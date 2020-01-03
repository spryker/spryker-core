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
use Spryker\Zed\CmsGui\Communication\Form\Constraint\TwigContent;
use Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueGlossaryForSearchType;
use Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueName;
use Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueUrl;
use Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsGlossaryFormTypeDataProvider;
use Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsPageFormTypeDataProvider;
use Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsVersionDataProvider;
use Spryker\Zed\CmsGui\Communication\Form\Glossary\CmsGlossaryFormType;
use Spryker\Zed\CmsGui\Communication\Form\Page\CmsPageFormType;
use Spryker\Zed\CmsGui\Communication\Form\Version\CmsVersionFormType;
use Spryker\Zed\CmsGui\Communication\Mapper\CmsVersionMapper;
use Spryker\Zed\CmsGui\Communication\Table\CmsPageTable;
use Spryker\Zed\CmsGui\Communication\Tabs\GlossaryTabs;
use Spryker\Zed\CmsGui\Communication\Tabs\PageTabs;
use Spryker\Zed\CmsGui\Communication\Updater\CmsGlossaryUpdater;
use Spryker\Zed\CmsGui\Communication\Updater\CmsGlossaryUpdaterInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Symfony\Component\Form\FormInterface;

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
            $this->getCmsFacade(),
            $this->getCmsPageTableExpanderPlugins()
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
     * @deprecated use instead getCmsVersionForm
     *
     * @param \Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsVersionDataProvider $cmsVersionDataProvider
     * @param int|null $idCmsPage
     * @param int|null $version
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCmsVersionForm(CmsVersionDataProvider $cmsVersionDataProvider, $idCmsPage = null, $version = null)
    {
        return $this->getCmsVersionForm($cmsVersionDataProvider, $idCmsPage, $version);
    }

    /**
     * @param \Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsVersionDataProvider $cmsVersionDataProvider
     * @param int|null $idCmsPage
     * @param int|null $version
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCmsVersionForm(CmsVersionDataProvider $cmsVersionDataProvider, ?int $idCmsPage = null, ?int $version = null): FormInterface
    {
        return $this->getFormFactory()->create(
            CmsVersionFormType::class,
            $cmsVersionDataProvider->getData($idCmsPage, $version),
            $cmsVersionDataProvider->getOptions($idCmsPage)
        );
    }

    /**
     * @deprecated use instead getCmsPageForm
     *
     * @param \Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsPageFormTypeDataProvider $cmsPageFormTypeDataProvider
     * @param int|null $idCmsPage
     * @param \Generated\Shared\Transfer\CmsPageTransfer|null $cmsPageTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCmsPageForm(
        CmsPageFormTypeDataProvider $cmsPageFormTypeDataProvider,
        $idCmsPage = null,
        ?CmsPageTransfer $cmsPageTransfer = null
    ): FormInterface {
        $cmsPageTransfer = $cmsPageTransfer ?: $cmsPageFormTypeDataProvider->getData($idCmsPage);

        return $this->getFormFactory()->create(
            CmsPageFormType::class,
            $cmsPageTransfer,
            $cmsPageFormTypeDataProvider->getOptions()
        );
    }

    /**
     * @deprecated use instead getCmsGlossaryForm
     *
     * @param \Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsGlossaryFormTypeDataProvider $cmsGlossaryFormTypeDataProvider
     * @param int $idCmsPage
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCmsGlossaryForm(CmsGlossaryFormTypeDataProvider $cmsGlossaryFormTypeDataProvider, $idCmsPage)
    {
        return $this->getCmsGlossaryForm($cmsGlossaryFormTypeDataProvider, $idCmsPage);
    }

    /**
     * @param \Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsGlossaryFormTypeDataProvider $cmsGlossaryFormTypeDataProvider
     * @param int $idCmsPage
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCmsGlossaryForm(CmsGlossaryFormTypeDataProvider $cmsGlossaryFormTypeDataProvider, int $idCmsPage): FormInterface
    {
        return $this->getFormFactory()->create(
            CmsGlossaryFormType::class,
            $cmsGlossaryFormTypeDataProvider->getData($idCmsPage),
            $cmsGlossaryFormTypeDataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsPageFormTypeDataProvider
     */
    public function createCmsPageFormTypeDataProvider()
    {
        return new CmsPageFormTypeDataProvider(
            $this->getCmsQueryContainer(),
            $this->getCmsFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsVersionDataProvider
     */
    public function createCmsVersionFormDataProvider()
    {
        return new CmsVersionDataProvider(
            $this->getCmsFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsGlossaryFormTypeDataProvider
     */
    public function createCmsGlossaryFormTypeDataProvider(): CmsGlossaryFormTypeDataProvider
    {
        return new CmsGlossaryFormTypeDataProvider(
            $this->getCmsFacade(),
            $this->createCmsGlossaryUpdater()
        );
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createUniqueUrlConstraint()
    {
        return new UniqueUrl([
            UniqueUrl::OPTION_URL_FACADE => $this->getUrlFacade(),
            UniqueUrl::OPTION_CMS_FACADE => $this->getCmsFacade(),
        ]);
    }

    /**
     * @return \Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueName
     */
    public function createUniqueNameConstraint()
    {
        return new UniqueName([
            UniqueName::OPTION_CMS_QUERY_CONTAINER => $this->getCmsQueryContainer(),
        ]);
    }

    /**
     * @return \Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueGlossaryForSearchType
     */
    public function createUniqueGlossaryForSearchTypeConstraint()
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
     * @return \Spryker\Zed\CmsGui\Communication\Mapper\CmsVersionMapper
     */
    public function createCmsVersionDataHelper()
    {
        return new CmsVersionMapper(
            $this->getCmsQueryContainer(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\CmsGui\Communication\Form\Constraint\TwigContent|\Symfony\Component\Validator\Constraint
     */
    public function createTwigContentConstraint()
    {
        return new TwigContent([
            TwigContent::OPTION_TWIG_ENVIRONMENT => $this->getTwigEnvironment(),
        ]);
    }

    /**
     * @return \Spryker\Zed\CmsGui\Communication\Updater\CmsGlossaryUpdaterInterface
     */
    public function createCmsGlossaryUpdater(): CmsGlossaryUpdaterInterface
    {
        return new CmsGlossaryUpdater($this->getCmsGlossaryAfterFindPlugins(), $this->getCmsGlossaryBeforeSavePlugins());
    }

    /**
     * @return \Twig\Environment
     */
    protected function getTwigEnvironment()
    {
        return $this->getProvidedDependency(CmsGuiDependencyProvider::TWIG_ENVIRONMENT);
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

    /**
     * @return \Spryker\Zed\CmsGui\Dependency\Service\CmsGuiToUtilEncodingInterface
     */
    public function getUtilEncodingService()
    {
        return $this->getProvidedDependency(CmsGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\CmsGui\Dependency\Plugin\CmsPageTableExpanderPluginInterface[]
     */
    protected function getCmsPageTableExpanderPlugins()
    {
        return $this->getProvidedDependency(CmsGuiDependencyProvider::PLUGINS_CMS_PAGE_TABLE_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\CmsGui\Dependency\Plugin\CreateGlossaryExpanderPluginInterface[]
     */
    public function getCreateGlossaryExpanderPlugins()
    {
        return $this->getProvidedDependency(CmsGuiDependencyProvider::PLUGINS_CREATE_GLOSSARY_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    public function getStoreRelationFormTypePlugin(): FormTypeInterface
    {
        return $this->getProvidedDependency(CmsGuiDependencyProvider::PLUGIN_STORE_RELATION_FORM_TYPE);
    }

    /**
     * @return \Spryker\Zed\CmsGuiExtension\Dependency\Plugin\CmsGlossaryAfterFindPluginInterface[]
     */
    public function getCmsGlossaryAfterFindPlugins(): array
    {
        return $this->getProvidedDependency(CmsGuiDependencyProvider::PLUGINS_CMS_GLOSSARY_AFTER_FIND);
    }

    /**
     * @return \Spryker\Zed\CmsGuiExtension\Dependency\Plugin\CmsGlossaryBeforeSavePluginInterface[]
     */
    public function getCmsGlossaryBeforeSavePlugins(): array
    {
        return $this->getProvidedDependency(CmsGuiDependencyProvider::PLUGINS_CMS_GLOSSARY_BEFORE_SAVE);
    }
}
