<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Communication\Controller;

use Generated\Shared\Transfer\PageKeyMappingTransfer;
use Generated\Shared\Transfer\PageTransfer;
use Pyz\Zed\Cms\CmsDependencyProvider;
use SprykerEngine\Shared\Config;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Shared\Yves\YvesConfig;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Cms\Business\CmsFacade;
use SprykerFeature\Zed\Cms\Communication\Form\CmsGlossaryForm;
use SprykerFeature\Zed\Cms\Communication\Table\CmsGlossaryTable;
use SprykerFeature\Zed\Cms\Communication\Table\CmsPageTable;
use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainer;
use Symfony\Component\HttpFoundation\Request;
use Functional\SprykerFeature\Zed\Glossary\Mock\LocaleFacade;
use Pyz\Zed\Cms\Communication\CmsDependencyContainer;

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
        $spyPageUrl = $this->getQueryContainer()
            ->queryPageWithTemplatesAndUrlByPageId($idPage)
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
            'url' => $spyPageUrl->getUrl(),
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

        $spyPageUrl = $this->getQueryContainer()
            ->queryPageWithTemplatesAndUrlByPageId($idPage)
            ->findOne()
        ;

        $pageUrlArray = $spyPageUrl->toArray();
        $tempFile = $this->getTemplatePhysicalAddress($pageUrlArray[CmsQueryContainer::TEMPLATE_PATH]);
        $placeholders = $this->findTemplatePlaceholders($tempFile);

        $searchArray = $request->get('search');

        if (isset($searchArray['value']) && !empty($searchArray['value'])) {
            $foundPlaceholders = [];
            foreach ($placeholders as $place) {
                if (stripos($place, $searchArray['value']) !== false) {
                    $foundPlaceholders[] = $place;
                }
            }
            $placeholders = $foundPlaceholders;
        }

        $table = $this->getDependencyContainer()
            ->createCmsGlossaryTable($idPage, $localeTransfer->getIdLocale(), $placeholders)
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
            ->createCmsGlossaryForm($idPage, null, $placeholder)
        ;

        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();

            $pageKeyMappingTransfer = (new PageKeyMappingTransfer())->fromArray($data, true);
            $spyGlossaryKey = $this->getQueryContainer()
                ->queryKey($data[CmsGlossaryForm::GLOSSARY_KEY])
                ->findOne()
            ;

            $pageKeyMappingTransfer->setFkGlossaryKey($spyGlossaryKey->getIdGlossaryKey());

            $this->getFacade()
                ->savePageKeyMapping($pageKeyMappingTransfer)
            ;

            $this->touchActivePage($idPage);

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
            ->createCmsGlossaryForm($idPage, $idMapping)
        ;

        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();

            $pageKeyMappingTransfer = (new PageKeyMappingTransfer())->fromArray($data, true);
            $spyGlossaryKey = $this->getQueryContainer()
                ->queryKey($data[CmsGlossaryForm::GLOSSARY_KEY])
                ->findOne()
            ;
            $pageKeyMappingTransfer->setFkGlossaryKey($spyGlossaryKey->getIdGlossaryKey());

            $this->getFacade()
                ->savePageKeyMapping($pageKeyMappingTransfer)
            ;

            $this->touchActivePage($idPage);

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
        // @todo Popup confirmation is needed.
        $idMapping = $request->get(CmsGlossaryTable::REQUEST_ID_MAPPING);
        $idPage = $request->get(CmsPageTable::REQUEST_ID_PAGE);

        $mappingGlossary = $this->getQueryContainer()
            ->queryGlossaryKeyMappingById($idMapping)
            ->findone()
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
            ->getProvidedDependency(CmsDependencyProvider::LOCALE_BUNDLE)
            ;
    }

    /**
     * @param $idPage
     *
     */
    private function touchActivePage($idPage)
    {
        $spyPage = $this->getQueryContainer()
            ->queryPageById($idPage)
            ->findOne()
        ;
        $pageTransfer = (new PageTransfer())->fromArray($spyPage->toArray());

        $this->getFacade()
            ->touchPageActive($pageTransfer)
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

        $handle = fopen($tempFile, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                preg_match('/<!-- CMS_PLACEHOLDER : "[a-zA-Z0-9]*" -->/', $line, $cmsPlaceholderLine);
                if (!empty($cmsPlaceholderLine)) {
                    preg_match('/"([^"]+)"/', $cmsPlaceholderLine[0], $placeholder);
                    $placeholderMap[] = $placeholder[1];
                }
            }
            fclose($handle);
        }

        return $placeholderMap;
    }

    /**
     * @param string $templatePath
     *
     * @throws \Exception
     * @return string
     */
    private function getTemplatePhysicalAddress($templatePath)
    {
        $config = Config::getInstance();
        $templatePath = substr($templatePath, 4);
        $physicalAddress = APPLICATION_ROOT_DIR . '/src/' . $config->get(SystemConfig::PROJECT_NAMESPACE) . '/Yves/Cms/Theme/' . $config->get(YvesConfig::YVES_THEME) . $templatePath;

        return $physicalAddress;
    }
}
