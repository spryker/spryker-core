<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication;

use Spryker\Zed\Cms\CmsDependencyProvider;
use Spryker\Zed\Cms\Communication\Form\CmsGlossaryForm;
use Spryker\Zed\Cms\Communication\Form\CmsRedirectForm;
use Spryker\Zed\Cms\Communication\Form\DataProvider\CmsGlossaryFormDataProvider;
use Spryker\Zed\Cms\Communication\Form\DataProvider\CmsRedirectFormDataProvider;
use Spryker\Zed\Cms\Communication\Table\CmsGlossaryTable;
use Spryker\Zed\Cms\Communication\Table\CmsRedirectTable;
use Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryFacadeInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleFacadeInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToUrlFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Cms\CmsConfig getConfig()
 */
class CmsCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Cms\Communication\Table\CmsRedirectTable
     */
    public function createCmsRedirectTable(): CmsRedirectTable
    {
        $urlQuery = $this->getQueryContainer()
            ->queryUrlsWithRedirect();

        return new CmsRedirectTable($urlQuery);
    }

    /**
     * @param int $idPage
     * @param int $fkLocale
     * @param array $placeholders
     * @param array $searchArray
     *
     * @return \Spryker\Zed\Cms\Communication\Table\CmsGlossaryTable
     */
    public function createCmsGlossaryTable(int $idPage, int $fkLocale, array $placeholders = [], array $searchArray = []): CmsGlossaryTable
    {
        $glossaryQuery = $this->getQueryContainer()
            ->queryGlossaryKeyMappingsWithKeyByPageId($idPage, $fkLocale);

        return new CmsGlossaryTable($glossaryQuery, $idPage, $placeholders, $searchArray);
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCmsRedirectForm(array $formData = [], array $formOptions = []): FormInterface
    {
        return $this->getFormFactory()->create(CmsRedirectForm::class, $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\Cms\Communication\Form\DataProvider\CmsRedirectFormDataProvider
     */
    public function createCmsRedirectFormDataProvider(): CmsRedirectFormDataProvider
    {
        return new CmsRedirectFormDataProvider($this->getQueryContainer());
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCmsGlossaryForm(array $formData = [], array $formOptions = []): FormInterface
    {
        return $this->getFormFactory()->create(CmsGlossaryForm::class, $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\Cms\Communication\Form\DataProvider\CmsGlossaryFormDataProvider
     */
    public function createCmsGlossaryFormDataProvider(): CmsGlossaryFormDataProvider
    {
        return new CmsGlossaryFormDataProvider($this->getQueryContainer());
    }

    /**
     * @param string $templateRelativePath
     *
     * @return array
     */
    public function getTemplateRealPaths(string $templateRelativePath): array
    {
        return $this->getConfig()
            ->getTemplateRealPaths($templateRelativePath);
    }

    /**
     * @return \Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleFacadeInterface
     */
    public function getLocaleFacade(): CmsToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(CmsDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Cms\Dependency\Facade\CmsToUrlFacadeInterface
     */
    public function getUrlFacade(): CmsToUrlFacadeInterface
    {
        return $this->getProvidedDependency(CmsDependencyProvider::FACADE_URL);
    }

    /**
     * @return \Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): CmsToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(CmsDependencyProvider::FACADE_GLOSSARY);
    }
}
