<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Communication\Controller;

use Generated\Shared\Transfer\PageKeyMappingTransfer;
use Generated\Shared\Transfer\PageTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Cms\Business\CmsFacade;
use SprykerFeature\Zed\Cms\CmsDependencyProvider;
use SprykerFeature\Zed\Cms\Communication\Form\CmsGlossaryForm;
use SprykerFeature\Zed\Cms\Communication\Table\CmsGlossaryTable;
use SprykerFeature\Zed\Cms\Communication\Table\CmsPageTable;
use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CmsDependencyContainer getDependencyContainer()
 * @method CmsQueryContainer getQueryContainer()
 * @method CmsFacade getFacade()
 */
class GlossaryController extends AbstractController
{
    const REDIRECT_ADDRESS = '/cms/glossary/';

    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idPage = $request->get(CmsPageTable::REQUEST_ID_PAGE);
        $pageUrl = $this->getQueryContainer()
            ->queryPageWithTemplatesAndUrlByIdPage($idPage)
            ->findOne()
        ;

        $localeTransfer = $this->getLocaleFacade()
            ->getCurrentLocale()
        ;

        $table = $this->getDependencyContainer()
            ->createCmsGlossaryTable($idPage, $localeTransfer->getIdLocale())
        ;

        return [
            'keyMaps' => $table->render(),
            'idPage' => $idPage,
            'url' => $pageUrl->getUrl(),
        ];
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function tableAction(Request $request)
    {
        $idPage = $request->get(CmsPageTable::REQUEST_ID_PAGE);

        $localeTransfer = $this->getLocaleFacade()
            ->getCurrentLocale()
        ;

        $pageUrl = $this->getQueryContainer()
            ->queryPageWithTemplatesAndUrlByIdPage($idPage)
            ->findOne()
        ;

        $pageUrlArray = $pageUrl->toArray();
        $tempFile = $this->getDependencyContainer()
            ->getTemplateRealPath($pageUrlArray[CmsQueryContainer::TEMPLATE_PATH])
        ;

        $placeholders = $this->findTemplatePlaceholders($tempFile);

        $table = $this->getDependencyContainer()
            ->createCmsGlossaryTable($idPage, $localeTransfer->getIdLocale(), $placeholders, $request->get('search'))
        ;

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function addAction(Request $request)
    {

        $idPage = $request->get(CmsPageTable::REQUEST_ID_PAGE);

        $placeholder = $request->get('placeholder');

        $form = $this->getDependencyContainer()
            ->createCmsGlossaryForm($idPage, null, $placeholder, $this->getFacade())
        ;

        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();

            $pageKeyMappingTransfer = $this->createKeyMappingTransfer($data);
            $this->getFacade()->savePageKeyMappingAndTouch($pageKeyMappingTransfer);

            return $this->redirectResponse(self::REDIRECT_ADDRESS . '?' . CmsPageTable::REQUEST_ID_PAGE . '=' . $idPage);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'idPage' => $idPage,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function editAction(Request $request)
    {
        $idMapping = $request->get(CmsGlossaryTable::REQUEST_ID_MAPPING);
        $idPage = $request->get(CmsPageTable::REQUEST_ID_PAGE);

        $form = $this->getDependencyContainer()
            ->createCmsGlossaryForm($idPage, $idMapping, null, $this->getFacade())
        ;

        $form->handleRequest();
        if ($form->isValid()) {
            $data = $form->getData();

            $pageKeyMappingTransfer = $this->createKeyMappingTransfer($data);
            $this->getFacade()->savePageKeyMappingAndTouch($pageKeyMappingTransfer);

            return $this->redirectResponse(self::REDIRECT_ADDRESS . '?' . CmsPageTable::REQUEST_ID_PAGE . '=' . $idPage);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'idPage' => $idPage,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $idMapping = $request->get(CmsGlossaryTable::REQUEST_ID_MAPPING);
        $idPage = $request->get(CmsPageTable::REQUEST_ID_PAGE);

        $mappingGlossary = $this->getQueryContainer()
            ->queryGlossaryKeyMappingById($idMapping)
            ->findOne()
        ;
        $pageTransfer = (new PageTransfer())->setIdCmsPage($idPage);
        $this->getFacade()
            ->deletePageKeyMapping($pageTransfer, $mappingGlossary->getPlaceholder())
        ;

        return $this->redirectResponse(self::REDIRECT_ADDRESS . '?' . CmsPageTable::REQUEST_ID_PAGE . '=' . $idPage);
    }

    /**
     * @return LocaleFacade
     */
    private function getLocaleFacade()
    {
        return $this->getDependencyContainer()
            ->getProvidedDependency(CmsDependencyProvider::FACADE_LOCALE)
            ;
    }

    /**
     * @param string $tempFile
     *
     * @return array
     */
    private function findTemplatePlaceholders($tempFile)
    {
        $placeholderMap = [];

        if (file_exists($tempFile)) {
            $fileContent = file_get_contents($tempFile);

            preg_match_all('/<!-- CMS_PLACEHOLDER : "[a-zA-Z0-9]*" -->/', $fileContent, $cmsPlaceholderLine);
            preg_match_all('/"([^"]+)"/', implode(' ', $cmsPlaceholderLine[0]), $placeholderMap);

            return $placeholderMap[1];
        }

        return $placeholderMap;
    }

    /**
     * @param $data
     *
     * @return $this
     */
    private function createKeyMappingTransfer($data)
    {
        $pageKeyMappingTransfer = (new PageKeyMappingTransfer())->fromArray($data, true);

        $glossaryKey = $this->getQueryContainer()
            ->queryKey($data[CmsGlossaryForm::GLOSSARY_KEY])
            ->findOne()
        ;

        $pageKeyMappingTransfer->setFkGlossaryKey($glossaryKey->getIdGlossaryKey());

        return $pageKeyMappingTransfer;
    }

}
