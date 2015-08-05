<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\CmsCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Cms\CmsDependencyProvider;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainer;

/**
 * @method CmsCommunication getFactory()
 */
class CmsDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function createCmsGrid(Request $request)
    {
        return $this->getFactory()->createGridCmsGrid(
            $this->getQueryContainer()->queryPagesWithTemplates(),
            $request
        );
    }

    /**
     * @return CmsQueryContainer
     */
    public function getQueryContainer()
    {
        return $this->getLocator()->cms()->queryContainer();
    }

    /**
     * @return CmsPageTable
     */
    public function createCmsPageTable()
    {
        $pageQuery = $this->getQueryContainer()->queryPageWithTemplatesAndUrls();

        return $this->getFactory()->createTableCmsPageTable($pageQuery);
    }

    /**
     * @return CmsRedirectTable
     */
    public function createCmsRedirectTable()
    {
        $urlQuery = $this->getQueryContainer()->queryUrlsWithRedirect();

        return $this->getFactory()->createTableCmsRedirectTable($urlQuery);
    }

    /**
     * @param int $idPage
     * @param int $fkLocale
     * @param array $placeholders
     *
     * @return CmsGlossaryTable
     */
    public function createCmsGlossaryTable($idPage, $fkLocale, $placeholders = null)
    {

        $glossaryQuery = $this->getQueryContainer()->queryGlossaryKeyMappingsWithKeyByPageId($idPage, $fkLocale);

        return $this->getFactory()->createTableCmsGlossaryTable($glossaryQuery,$idPage, $placeholders);
    }

    /**
     * @param string $formType
     * @param int $idPage
     *
     * @return CmsPageForm
     */
    public function createCmsPageForm($formType, $idPage = null)
    {

        $pageUrlQuery = null;

        if($idPage)
            $pageUrlQuery = $this->getQueryContainer()->queryPageWithTemplatesAndUrlByPageId($idPage);

        $templateQuery = $this->getQueryContainer()
            ->queryTemplates();

        $urlFacade = $this->getProvidedDependency(CmsDependencyProvider::URL_BUNDLE);

        return $this->getFactory()
            ->createFormCmsPageForm($templateQuery, $pageUrlQuery, $formType, $idPage, $urlFacade)
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
        $queryUrl = $this->getQueryContainer()->queryUrlByIdWithRedirect($idUrl);

        $urlFacade = $this->getProvidedDependency(CmsDependencyProvider::URL_BUNDLE);

        return $this->getFactory()
            ->createFormCmsRedirectForm($queryUrl, $formType, $urlFacade)
            ;
    }

    /**
     * @param string $formType
     * @param int $idPage
     * @param int $idMapping
     * @param array $placeholder
     *
     * @return CmsGlossaryForm
     */
    public function createCmsGlossaryForm($idPage, $idMapping = null, $placeholder = null)
    {
        $glossaryQuery = null;

        if($idMapping)
            $glossaryQuery = $this->getQueryContainer()->queryGlossaryKeyMappingWithKeyById($idMapping);

        return $this->getFactory()
            ->createFormCmsGlossaryForm($glossaryQuery, $idPage, $idMapping, $placeholder)
            ;
    }
}
