<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Communication;

use Spryker\Zed\Cms\Communication\Form\CmsBlockForm;
use Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Cms\Business\CmsFacade;
use Spryker\Zed\Cms\CmsConfig;
use Spryker\Zed\Cms\CmsDependencyProvider;
use Spryker\Zed\Cms\Communication\Form\CmsGlossaryForm;
use Spryker\Zed\Cms\Communication\Form\CmsPageForm;
use Spryker\Zed\Cms\Communication\Form\CmsRedirectForm;
use Spryker\Zed\Cms\Communication\Table\CmsBlockTable;
use Spryker\Zed\Cms\Communication\Table\CmsGlossaryTable;
use Spryker\Zed\Cms\Communication\Table\CmsPageTable;
use Spryker\Zed\Cms\Communication\Table\CmsRedirectTable;
use Spryker\Zed\Cms\Persistence\CmsQueryContainer;
use Symfony\Component\Form\FormInterface;

/**
 * @method CmsQueryContainer getQueryContainer()
 * @method CmsConfig getConfig()
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
     * @param string $formType
     * @param int $idPage
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCmsPageForm($formType, $idPage = null)
    {
        $pageUrlByIdQuery = $this->getQueryContainer()
            ->queryPageWithTemplatesAndUrlByIdPage($idPage);

        $templateQuery = $this->getQueryContainer()
            ->queryTemplates();

        $urlFacade = $this->getProvidedDependency(CmsDependencyProvider::FACADE_URL);

        $form = new CmsPageForm($templateQuery, $pageUrlByIdQuery, $urlFacade, $formType, $idPage);

        return $this->createForm($form);
    }

    /**
     * @param string $formType
     * @param int $idCmsBlock
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCmsBlockForm($formType, $idCmsBlock = null)
    {
        $blockPageByIdQuery = $this->getQueryContainer()
            ->queryPageWithTemplatesAndBlocksById($idCmsBlock);

        $templateQuery = $this->getQueryContainer()
            ->queryTemplates();

        $form = new CmsBlockForm($templateQuery, $blockPageByIdQuery, $formType, $idCmsBlock);

        return $this->createForm($form);
    }

    /**
     * @param string $formType
     * @param int $idUrl
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCmsRedirectForm($formType, $idUrl = null)
    {
        $queryUrlById = $this->getQueryContainer()
            ->queryUrlByIdWithRedirect($idUrl);

        $urlFacade = $this->getProvidedDependency(CmsDependencyProvider::FACADE_URL);

        $form = new CmsRedirectForm($queryUrlById, $urlFacade, $formType);

        return $this->createForm($form);
    }

    /**
     * @param int $idPage
     * @param int $idMapping
     * @param array $placeholder
     * @param \Spryker\Zed\Cms\Business\CmsFacade $cmsFacade
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCmsGlossaryForm($idPage, $idMapping, $placeholder, $cmsFacade)
    {
        $glossaryMappingByIdQuery = $this->getQueryContainer()
            ->queryGlossaryKeyMappingWithKeyById($idMapping);

        $form = new CmsGlossaryForm($glossaryMappingByIdQuery, $cmsFacade, $idPage, $idMapping, $placeholder);

        return $this->createForm($form);
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
