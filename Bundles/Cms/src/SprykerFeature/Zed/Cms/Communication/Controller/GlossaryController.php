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
use SprykerFeature\Zed\Cms\Communication\Form\CmsPageForm;
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

    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idPage = $request->get('id_page');
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
     * @return JsonResponse
     */
    public function tableAction(Request $request)
    {
        $idPage = $request->get('id_page');

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
            foreach($placeholders as $place){
                if(stripos($place,$searchArray['value']) !== false){
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
     * @return array
     */
    public function addAction(Request $request)
    {

        $idPage = $request->get('id_page');

        $placeholder = $request->get('placeholder');

        $form = $this->getDependencyContainer()
            ->createCmsGlossaryForm('add', $idPage, null, $placeholder)
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

            return $this->redirectResponse('/cms/glossary/?id_page=' . $idPage);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'idPage' => $idPage,
        ]);
    }

    public function editAction(Request $request)
    {
        $idMapping = $request->get('id_mapping');
        $idPage = $request->get('id_page');

        $form = $this->getDependencyContainer()
            ->createCmsGlossaryForm('update', $idPage, $idMapping)
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

            return $this->redirectResponse('/cms/glossary/?id_page=' . $idPage);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'idPage' => $idPage,
        ]);
    }

    public function deleteAction(Request $request)
    {
        $idMapping = $request->get('id_mapping');
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

    private function touchActivePage($idPage)
    {
        $spyPage = $this->getQueryContainer()
            ->queryPageById($idPage)
            ->findOne()
        ;
        $pageTransfer = (new PageTransfer())->fromArray($spyPage->toArray());

        return $this->getFacade()
            ->touchPageActive($pageTransfer)
            ;
    }

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

    private function getTemplatePhysicalAddress($templatePath)
    {
        $config = Config::getInstance();
        $templatePath = substr($templatePath, 4);
        $physicalAddress = APPLICATION_ROOT_DIR . '/src/' . $config->get(SystemConfig::PROJECT_NAMESPACE) . '/Yves/Cms/Theme/' . $config->get(YvesConfig::YVES_THEME) . $templatePath;

        return $physicalAddress;
    }
}
