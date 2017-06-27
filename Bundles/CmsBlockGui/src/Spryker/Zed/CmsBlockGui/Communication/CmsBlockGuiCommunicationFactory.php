<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Communication;

use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Spryker\Zed\CmsBlockGui\CmsBlockGuiDependencyProvider;
use Spryker\Zed\CmsBlockGui\Communication\Form\Block\CmsBlockForm;
use Spryker\Zed\CmsBlockGui\Communication\Form\DataProvider\CmsBlockFormDataProvider;
use Spryker\Zed\CmsBlockGui\Communication\Form\DataProvider\CmsBlockGlossaryFormDataProvider;
use Spryker\Zed\CmsBlockGui\Communication\Form\Glossary\CmsBlockGlossaryForm;
use Spryker\Zed\CmsBlockGui\Communication\Form\Glossary\CmsBlockGlossaryPlaceholderForm;
use Spryker\Zed\CmsBlockGui\Communication\Plugin\CmsBlockViewPluginInterface;
use Spryker\Zed\CmsBlockGui\Communication\Table\CmsBlockTable;
use Spryker\Zed\CmsBlockGui\Communication\Tabs\CmsBlockGlossaryTabs;
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
     * @return CmsBlockViewPluginInterface[]
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
    public function createCmsBlockForm(CmsBlockFormDataProvider $cmsBlockFormDataProvider, $idCmsBlock = null)
    {
        $cmsBlockForm = new CmsBlockForm(
            $this->getCmsBlockQueryContainer(),
            $this->getCmsBlockFormPlugins()
        );

        return $this->getFormFactory()->create(
            $cmsBlockForm,
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

        return new CmsBlockTable($cmsBlockQuery);
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
            $this->getCmsBlockFacade()
        );
    }

    /**
     * @param \Spryker\Zed\CmsBlockGui\Communication\Form\DataProvider\CmsBlockGlossaryFormDataProvider $cmsBlockGlossaryFormDataProvider
     * @param int $idCmsBlock
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCmsBlockGlossaryForm(
        CmsBlockGlossaryFormDataProvider $cmsBlockGlossaryFormDataProvider,
        $idCmsBlock
    ) {
        $cmsBlockGlossaryForm = $this->createCmsBlockGlossaryFormType();

        return $this->getFormFactory()
            ->create(
                $cmsBlockGlossaryForm,
                $cmsBlockGlossaryFormDataProvider->getData($idCmsBlock),
                $cmsBlockGlossaryFormDataProvider->getOptions()
            );
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    protected function createCmsBlockGlossaryFormType()
    {
        return new CmsBlockGlossaryForm(
            $this->createCmsBlockGlossaryPlaceholderFormType()
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlockGui\Communication\Form\Glossary\CmsBlockGlossaryPlaceholderForm
     */
    protected function createCmsBlockGlossaryPlaceholderFormType()
    {
        return new CmsBlockGlossaryPlaceholderForm(
            $this->getCmsBlockFacade()
        );
    }

}
