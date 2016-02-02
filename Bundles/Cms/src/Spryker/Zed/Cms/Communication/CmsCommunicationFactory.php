<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Communication;

use Spryker\Zed\Cms\Business\CmsFacade;
use Spryker\Zed\Cms\Communication\Form\CmsBlockForm;
use Spryker\Zed\Cms\Communication\Form\DataProvider\CmsBlockFormDataProvider;
use Spryker\Zed\Cms\Communication\Form\DataProvider\CmsGlossaryFormDataProvider;
use Spryker\Zed\Cms\Communication\Form\DataProvider\CmsPageFormDataProvider;
use Spryker\Zed\Cms\Communication\Form\DataProvider\CmsRedirectFormDataProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Cms\CmsDependencyProvider;
use Spryker\Zed\Cms\Communication\Form\CmsGlossaryForm;
use Spryker\Zed\Cms\Communication\Form\CmsPageForm;
use Spryker\Zed\Cms\Communication\Form\CmsRedirectForm;
use Spryker\Zed\Cms\Communication\Table\CmsBlockTable;
use Spryker\Zed\Cms\Communication\Table\CmsGlossaryTable;
use Spryker\Zed\Cms\Communication\Table\CmsPageTable;
use Spryker\Zed\Cms\Communication\Table\CmsRedirectTable;

/**
 * @method \Spryker\Zed\Cms\Persistence\CmsQueryContainer getQueryContainer()
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
     * @param int $idLocale
     *
     * @return \Spryker\Zed\Cms\Communication\Table\CmsBlockTable
     */
    public function createCmsBlockTable($idLocale)
    {
        $blockQuery = $this->getQueryContainer()
            ->queryPageWithTemplatesAndBlocks($idLocale);

        return new CmsBlockTable($blockQuery);
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
    public function createCmsGlossaryTable($idPage, $fkLocale, array $placeholders = null, array $searchArray = null)
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
        $urlFacade = $this->getProvidedDependency(CmsDependencyProvider::FACADE_URL);
        $cmsPageForm = new CmsPageForm($urlFacade);

        return $this->getFormFactory()->create($cmsPageForm, $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\Cms\Communication\Form\DataProvider\CmsPageFormDataProvider
     */
    public function createCmsPageFormDataProvider()
    {
        return new CmsPageFormDataProvider($this->getQueryContainer());
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCmsBlockForm(array $formData = [], array $formOptions = [])
    {
        $formType = new CmsBlockForm($this->getQueryContainer());

        return $this->getFormFactory()->create($formType, $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\Cms\Communication\Form\DataProvider\CmsBlockFormDataProvider
     */
    public function createCmsBlockFormDataProvider()
    {
        return new CmsBlockFormDataProvider($this->getQueryContainer());
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCmsRedirectForm(array $formData = [], array $formOptions = [])
    {
        $urlFacade = $this->getProvidedDependency(CmsDependencyProvider::FACADE_URL);
        $cmsRedirectFormType = new CmsRedirectForm($urlFacade);

        return $this->getFormFactory()->create($cmsRedirectFormType, $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\Cms\Communication\Form\DataProvider\CmsRedirectFormDataProvider
     */
    public function createCmsRedirectFormDataProvider()
    {
        return new CmsRedirectFormDataProvider($this->getQueryContainer());
    }

    /**
     * @param \Spryker\Zed\Cms\Business\CmsFacade $cmsFacade
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCmsGlossaryForm(CmsFacade $cmsFacade, array $formData = [], array $formOptions = [])
    {
        $cmsGlossaryFormType = new CmsGlossaryForm($cmsFacade);

        return $this->getFormFactory()->create($cmsGlossaryFormType, $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\Cms\Communication\Form\DataProvider\CmsGlossaryFormDataProvider
     */
    public function createCmsGlossaryFormDataProvider()
    {
        return new CmsGlossaryFormDataProvider($this->getQueryContainer());
    }

    /**
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
     * @return \Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::FACADE_LOCALE);
    }

}
