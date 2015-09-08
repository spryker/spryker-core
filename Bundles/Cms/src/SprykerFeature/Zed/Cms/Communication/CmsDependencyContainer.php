<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\CmsCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Cms\CmsDependencyProvider;

/**
 * @method CmsCommunication getFactory()
 */
class CmsDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return CmsPageTable
     */
    public function createCmsPageTable()
    {
        $pageQuery = $this->getQueryContainer()
            ->queryPageWithTemplatesAndUrls()
        ;

        return $this->getFactory()
            ->createTableCmsPageTable($pageQuery)
            ;
    }

    /**
     * @return CmsBlockTable
     */
    public function createCmsBlockTable()
    {
        $blockQuery = $this->getQueryContainer()
            ->queryPageWithTemplatesAndBlocks()
        ;

        return $this->getFactory()
            ->createTableCmsBlockTable($blockQuery)
            ;
    }

    /**
     * @return CmsRedirectTable
     */
    public function createCmsRedirectTable()
    {
        $urlQuery = $this->getQueryContainer()
            ->queryUrlsWithRedirect()
        ;

        return $this->getFactory()
            ->createTableCmsRedirectTable($urlQuery)
            ;
    }

    /**
     * @param int $idPage
     * @param int $fkLocale
     * @param array $placeholders
     * @param array $searchArray
     *
     * @return CmsGlossaryTable
     */
    public function createCmsGlossaryTable($idPage, $fkLocale, array $placeholders = null, array $searchArray = null)
    {
        $glossaryQuery = $this->getQueryContainer()
            ->queryGlossaryKeyMappingsWithKeyByPageId($idPage, $fkLocale)
        ;

        return $this->getFactory()
            ->createTableCmsGlossaryTable($glossaryQuery, $idPage, $placeholders, $searchArray)
            ;
    }

    /**
     * @param string $formType
     * @param int $idPage
     *
     * @return CmsPageForm
     */
    public function createCmsPageForm($formType, $idPage = null)
    {
        $pageUrlByIdQuery = $this->getQueryContainer()
            ->queryPageWithTemplatesAndUrlByIdPage($idPage)
        ;

        $templateQuery = $this->getQueryContainer()
            ->queryTemplates()
        ;

        $urlFacade = $this->getProvidedDependency(CmsDependencyProvider::FACADE_URL);

        return $this->getFactory()
            ->createFormCmsPageForm($templateQuery, $pageUrlByIdQuery, $urlFacade,
                $this->getFactory()->createFormConstraintCmsConstraint(), $formType, $idPage)
            ;
    }

    /**
     * @param string $formType
     * @param int $idCmsBlock
     *
     * @return CmsPageForm
     */
    public function createCmsBlockForm($formType, $idCmsBlock = null)
    {
        $blockPageByIdQuery = $this->getQueryContainer()
            ->queryPageWithTemplatesAndBlocksById($idCmsBlock)
        ;

        $templateQuery = $this->getQueryContainer()
            ->queryTemplates()
        ;

        return $this->getFactory()
            ->createFormCmsBlockForm($templateQuery, $blockPageByIdQuery,
                $this->getFactory()->createFormConstraintCmsConstraint(), $formType, $idCmsBlock)
            ;
    }

    /**
     * @param string $formType
     * @param int $idUrl
     *
     * @return CmsRedirectForm
     */
    public function createCmsRedirectForm($formType, $idUrl = null)
    {
        $queryUrlById = $this->getQueryContainer()
            ->queryUrlByIdWithRedirect($idUrl)
        ;

        $urlFacade = $this->getProvidedDependency(CmsDependencyProvider::FACADE_URL);

        return $this->getFactory()
            ->createFormCmsRedirectForm($queryUrlById, $urlFacade,
                $this->getFactory()->createFormConstraintCmsConstraint(), $formType)
            ;
    }

    /**
     * @param int $idPage
     * @param int $idMapping
     * @param array $placeholder
     * @param CmsFacade $cmsFacade
     *
     * @return CmsGlossaryForm
     */
    public function createCmsGlossaryForm($idPage, $idMapping = null, $placeholder = null, $cmsFacade)
    {
        $glossaryMappingByIdQuery = $this->getQueryContainer()
            ->queryGlossaryKeyMappingWithKeyById($idMapping)
        ;

        return $this->getFactory()
            ->createFormCmsGlossaryForm($glossaryMappingByIdQuery, $cmsFacade,
                $this->getFactory()->createFormConstraintCmsConstraint(), $idPage, $idMapping, $placeholder)
            ;
    }

    /**
     * @param string $templateRelativePath
     *
     * @return string
     */
    public function getTemplateRealPath($templateRelativePath)
    {
        return $this->getConfig()
            ->getTemplateRealPath($templateRelativePath)
            ;
    }
}
