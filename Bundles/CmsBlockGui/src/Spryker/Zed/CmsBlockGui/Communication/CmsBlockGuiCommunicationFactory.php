<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Communication;

use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Spryker\Zed\CmsBlockGui\CmsBlockGuiDependencyProvider;
use Spryker\Zed\CmsBlockGui\Communication\Form\Block\CmsBlockForm;
use Spryker\Zed\CmsBlockGui\Communication\Form\Constraint\TwigContent;
use Spryker\Zed\CmsBlockGui\Communication\Form\DataProvider\CmsBlockFormDataProvider;
use Spryker\Zed\CmsBlockGui\Communication\Form\DataProvider\CmsBlockGlossaryFormDataProvider;
use Spryker\Zed\CmsBlockGui\Communication\Form\Glossary\CmsBlockGlossaryForm;
use Spryker\Zed\CmsBlockGui\Communication\Form\Glossary\CmsBlockGlossaryPlaceholderForm;
use Spryker\Zed\CmsBlockGui\Communication\Form\Glossary\CmsBlockGlossaryPlaceholderTranslationForm;
use Spryker\Zed\CmsBlockGui\Communication\Table\CmsBlockTable;
use Spryker\Zed\CmsBlockGui\Communication\Tabs\CmsBlockGlossaryTabs;
use Spryker\Zed\CmsBlockGui\Communication\Updater\CmsBlockGlossaryUpdater;
use Spryker\Zed\CmsBlockGui\Communication\Updater\CmsBlockGlossaryUpdaterInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CmsBlockGui\CmsBlockGuiConfig getConfig()
 */
class CmsBlockGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CmsBlockGui\Communication\Form\DataProvider\CmsBlockFormDataProvider
     */
    public function createCmsBlockFormDataProvider()
    {
        return new CmsBlockFormDataProvider(
            $this->getCmsBlockQueryContainer(),
            $this->getCmsBlockFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlockGui\Dependency\QueryContainer\CmsBlockGuiToCmsBlockQueryContainerInterface
     */
    public function getCmsBlockQueryContainer()
    {
        return $this->getProvidedDependency(CmsBlockGuiDependencyProvider::QUERY_CONTAINER_CMS_BLOCK);
    }

    /**
     * @return \Spryker\Zed\CmsBlockGui\Dependency\Facade\CmsBlockGuiToCmsBlockInterface
     */
    public function getCmsBlockFacade()
    {
        return $this->getProvidedDependency(CmsBlockGuiDependencyProvider::FACADE_CMS_BLOCK);
    }

    /**
     * @return \Spryker\Zed\CmsBlockGui\Dependency\Facade\CmsBlockGuiToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(CmsBlockGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\CmsBlockGui\Communication\Plugin\CmsBlockFormPluginInterface[]
     */
    public function getCmsBlockFormPlugins()
    {
        return $this->getProvidedDependency(CmsBlockGuiDependencyProvider::PLUGINS_CMS_BLOCK_FORM);
    }

    /**
     * @return \Spryker\Zed\CmsBlockGui\Communication\Plugin\CmsBlockViewPluginInterface[]
     */
    public function getCmsBlockViewPlugins()
    {
        return $this->getProvidedDependency(CmsBlockGuiDependencyProvider::PLUGINS_CMS_BLOCK_VIEW);
    }

    /**
     * @param \Spryker\Zed\CmsBlockGui\Communication\Form\DataProvider\CmsBlockFormDataProvider $cmsBlockFormDataProvider
     * @param int|null $idCmsBlock
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCmsBlockForm(CmsBlockFormDataProvider $cmsBlockFormDataProvider, $idCmsBlock = null)
    {
        return $this->getFormFactory()->create(
            CmsBlockForm::class,
            $cmsBlockFormDataProvider->getData($idCmsBlock),
            $cmsBlockFormDataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlockGui\Communication\Table\CmsBlockTable
     */
    public function createCmsBlockTable()
    {
        $cmsBlockQuery = $this->getCmsBlockQueryContainer()
            ->queryCmsBlockWithTemplate();

        return new CmsBlockTable(
            $cmsBlockQuery,
            $this->getCmsBlockQueryContainer()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $glossaryTransfer
     *
     * @return \Spryker\Zed\CmsBlockGui\Communication\Tabs\CmsBlockGlossaryTabs
     */
    public function createCmsBlockPlaceholderTabs(CmsBlockGlossaryTransfer $glossaryTransfer)
    {
        return new CmsBlockGlossaryTabs($glossaryTransfer);
    }

    /**
     * @return \Spryker\Zed\CmsBlockGui\Communication\Form\DataProvider\CmsBlockGlossaryFormDataProvider
     */
    public function createCmsBlockGlossaryFormDataProvider()
    {
        return new CmsBlockGlossaryFormDataProvider(
            $this->getCmsBlockFacade(),
            $this->createCmsBlockGlossaryUpdater()
        );
    }

    /**
     * @param \Spryker\Zed\CmsBlockGui\Communication\Form\DataProvider\CmsBlockGlossaryFormDataProvider $cmsBlockGlossaryFormDataProvider
     * @param int $idCmsBlock
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCmsBlockGlossaryForm(
        CmsBlockGlossaryFormDataProvider $cmsBlockGlossaryFormDataProvider,
        $idCmsBlock
    ) {
        return $this->getFormFactory()
            ->create(
                CmsBlockGlossaryForm::class,
                $cmsBlockGlossaryFormDataProvider->getData($idCmsBlock),
                $cmsBlockGlossaryFormDataProvider->getOptions()
            );
    }

    /**
     * @return string
     */
    public function getCmsBlockGlossaryPlaceholderTranslationFormType()
    {
        return CmsBlockGlossaryPlaceholderTranslationForm::class;
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
     * @return \Twig\Environment
     */
    protected function getTwigEnvironment()
    {
        return $this->getProvidedDependency(CmsBlockGuiDependencyProvider::TWIG_ENVIRONMENT);
    }

    /**
     * @return string
     */
    public function getCmsBlockGlossaryPlaceholderFormType()
    {
        return CmsBlockGlossaryPlaceholderForm::class;
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    public function getStoreRelationFormTypePlugin()
    {
        return $this->getProvidedDependency(CmsBlockGuiDependencyProvider::PLUGIN_STORE_RELATION_FORM_TYPE);
    }

    /**
     * @return \Spryker\Zed\CmsBlockGui\Communication\Updater\CmsBlockGlossaryUpdaterInterface
     */
    public function createCmsBlockGlossaryUpdater(): CmsBlockGlossaryUpdaterInterface
    {
        return new CmsBlockGlossaryUpdater(
            $this->getCmsBlockGlossaryAfterFindPlugins(),
            $this->getCmsBlockGlossaryBeforeSavePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlockGuiExtension\Dependency\Plugin\CmsBlockGlossaryAfterFindPluginInterface[]
     */
    public function getCmsBlockGlossaryAfterFindPlugins(): array
    {
        return $this->getProvidedDependency(CmsBlockGuiDependencyProvider::PLUGINS_CMS_BLOCK_GLOSSARY_AFTER_FIND);
    }

    /**
     * @return \Spryker\Zed\CmsBlockGuiExtension\Dependency\Plugin\CmsBlockGlossaryBeforeSavePluginInterface[]
     */
    public function getCmsBlockGlossaryBeforeSavePlugins(): array
    {
        return $this->getProvidedDependency(CmsBlockGuiDependencyProvider::PLUGINS_CMS_BLOCK_GLOSSARY_BEFORE_SAVE);
    }
}
