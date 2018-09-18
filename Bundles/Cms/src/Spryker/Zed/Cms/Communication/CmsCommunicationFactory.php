<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication;

use Spryker\Zed\Cms\Business\CmsFacade;
use Spryker\Zed\Cms\CmsDependencyProvider;
use Spryker\Zed\Cms\Communication\Form\CmsGlossaryForm;
use Spryker\Zed\Cms\Communication\Form\CmsPageForm;
use Spryker\Zed\Cms\Communication\Form\CmsRedirectForm;
use Spryker\Zed\Cms\Communication\Form\DataProvider\CmsGlossaryFormDataProvider;
use Spryker\Zed\Cms\Communication\Form\DataProvider\CmsPageFormDataProvider;
use Spryker\Zed\Cms\Communication\Form\DataProvider\CmsPageLocalizedAttributesFormDataProvider;
use Spryker\Zed\Cms\Communication\Form\DataProvider\CmsRedirectFormDataProvider;
use Spryker\Zed\Cms\Communication\Table\CmsGlossaryTable;
use Spryker\Zed\Cms\Communication\Table\CmsPageTable;
use Spryker\Zed\Cms\Communication\Table\CmsRedirectTable;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Cms\CmsConfig getConfig()
 */
class CmsCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Cms\Communication\Table\CmsPageTable
     */
    public function createCmsPageTable()
    {
        $pageQuery = $this->getQueryContainer()
            ->queryPageWithTemplatesAndUrls();

        return new CmsPageTable($pageQuery);
    }

    /**
     * @return \Spryker\Zed\Cms\Communication\Table\CmsRedirectTable
     */
    public function createCmsRedirectTable()
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
    public function createCmsGlossaryTable($idPage, $fkLocale, array $placeholders = [], array $searchArray = [])
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
    public function createCmsPageForm(array $formData = [], array $formOptions = [])
    {
        return $this->getFormFactory()->create(CmsPageForm::class, $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\Cms\Communication\Form\DataProvider\CmsPageFormDataProvider
     */
    public function createCmsPageFormDataProvider()
    {
        return new CmsPageFormDataProvider(
            $this->getQueryContainer(),
            $this->getLocaleFacade(),
            $this->createCmsPageLocalizedAttributesFormDataProvider()
        );
    }

    /**
     * @return \Spryker\Zed\Cms\Communication\Form\DataProvider\CmsPageLocalizedAttributesFormDataProvider
     */
    public function createCmsPageLocalizedAttributesFormDataProvider()
    {
        return new CmsPageLocalizedAttributesFormDataProvider($this->getQueryContainer());
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCmsRedirectForm(array $formData = [], array $formOptions = [])
    {
        return $this->getFormFactory()->create(CmsRedirectForm::class, $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\Cms\Communication\Form\DataProvider\CmsRedirectFormDataProvider
     */
    public function createCmsRedirectFormDataProvider()
    {
        return new CmsRedirectFormDataProvider($this->getQueryContainer());
    }

    /**
     * @deprecated Use getCmsGlossaryForm() instead.
     *
     * @param \Spryker\Zed\Cms\Business\CmsFacade $cmsFacade
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCmsGlossaryForm(CmsFacade $cmsFacade, array $formData = [], array $formOptions = [])
    {
        return $this->getCmsGlossaryForm($formData, $formOptions);
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCmsGlossaryForm(array $formData = [], array $formOptions = [])
    {
        return $this->getFormFactory()->create(CmsGlossaryForm::class, $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\Cms\Communication\Form\DataProvider\CmsGlossaryFormDataProvider
     */
    public function createCmsGlossaryFormDataProvider()
    {
        return new CmsGlossaryFormDataProvider($this->getQueryContainer());
    }

    /**
     * @deprecated use getTemplateRealPaths instead
     *
     * @param string $templateRelativePath
     *
     * @return string
     */
    public function getTemplateRealPath($templateRelativePath)
    {
        return $this->getConfig()
            ->getTemplateRealPath($templateRelativePath);
    }

    /**
     * @param string $templateRelativePath
     *
     * @return array
     */
    public function getTemplateRealPaths($templateRelativePath)
    {
        return $this->getConfig()
            ->getTemplateRealPaths($templateRelativePath);
    }

    /**
     * @return \Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface
     */
    public function getUrlFacade()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::FACADE_URL);
    }

    /**
     * @return \Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface
     */
    public function getGlossaryFacade()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::FACADE_GLOSSARY);
    }
}
