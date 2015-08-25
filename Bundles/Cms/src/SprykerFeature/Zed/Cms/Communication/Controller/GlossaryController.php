<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Communication\Controller;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageKeyMappingTransfer;
use Generated\Shared\Transfer\PageTransfer;
use SprykerFeature\Shared\Cms\CmsConfig;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Cms\Business\CmsFacade;
use SprykerFeature\Zed\Cms\CmsDependencyProvider;
use SprykerFeature\Zed\Cms\Communication\Form\CmsGlossaryForm;
use SprykerFeature\Zed\Cms\Communication\Table\CmsGlossaryTable;
use SprykerFeature\Zed\Cms\Communication\Table\CmsPageTable;
use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainer;
use SprykerFeature\Zed\Cms\Persistence\Propel\Base\SpyCmsPage;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CmsDependencyContainer getDependencyContainer()
 * @method CmsQueryContainer getQueryContainer()
 * @method CmsFacade getFacade()
 */
class GlossaryController extends AbstractController
{
    const REDIRECT_ADDRESS = '/cms/glossary/';
    const SEARCH_LIMIT = 10;
    const LOCALE = 'locale_';
    const ID_FORM = 'id-form';
    const TYPE = 'type';

    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idPage = $request->get(CmsPageTable::REQUEST_ID_PAGE);
        $idForm = $request->get(self::ID_FORM);
        $title = null;
        $type = CmsConfig::RESOURCE_TYPE_PAGE;

        $block = $this->getQueryContainer()
            ->queryBlockByIdPage($idPage)
            ->findOne()
        ;

        $pageUrl = $this->getQueryContainer()
            ->queryPageWithTemplatesAndUrlByIdPage($idPage)
            ->findOne()
        ;

        if (null === $block) {
            $title = $pageUrl->getUrl();
        } else {
            $type = CmsConfig::RESOURCE_TYPE_BLOCK;
            $title = $block->getName();
        }
        $localeTransfer = $this->getLocaleFacade()
            ->getCurrentLocale()
        ;

        $placeholders = $this->findPagePlaceholders($pageUrl);
        $glossaryMappingArray = $this->extractGlossaryMapping($idPage, $localeTransfer);
        $forms = [];
        $formViews = [];

        foreach ($placeholders as $place) {
            $form = $this->createPlaceholderForm($glossaryMappingArray, $place, $idPage);
            $forms[] = $form;
            $formViews[] = $form->createView();
        }
        if (null !== $idForm) {
            return $this->handleAjaxRequest($forms, $idForm, $localeTransfer);
        }

        return [
            'idPage' => $idPage,
            'title' => $title,
            'type' => $type,
            'forms' => $formViews,
        ];
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
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function searchAction(Request $request)
    {
        $value = $request->get('value');
        $key = $request->get('key');

        $searchedItems = $this->searchGlossaryKeysAndTranslations($value, $key);

        $result = [];
        foreach ($searchedItems as $trans) {
            $result[] = [
                'key' => $trans->getLabel(),
                'value' => $trans->getValue(),
            ];
        }

        return $this->jsonResponse($result);
    }

    /**
     * @param string $value
     * @param string $key
     *
     * @return array
     */
    private function searchGlossaryKeysAndTranslations($value, $key)
    {
        $searchedItems = [];
        if (null !== $value) {
            $searchedItems = $this->getQueryContainer()
                ->queryTranslationWithKeyByValue($value)
                ->limit(self::SEARCH_LIMIT)
                ->find()
            ;
            return $searchedItems;
        } else if (null !== $key) {
            $searchedItems = $this->getQueryContainer()
                ->queryKeyWithTranslationByKey($key)
                ->limit(self::SEARCH_LIMIT)
                ->find()
            ;
        }
        return $searchedItems;
    }


    /**
     * @param array $data
     *
     * @return PageKeyMappingTransfer
     */
    private function createKeyMappingTransfer(array $data)
    {
        $pageKeyMappingTransfer = (new PageKeyMappingTransfer())->fromArray($data, true);
        $hasPageMapping = $this->getFacade()->hasPagePlaceholderMapping($data['fkPage'], $data['placeholder']);
        if ($hasPageMapping) {
            $pageKeyMappingFound = $this->getFacade()
                ->getPagePlaceholderMapping($data['fkPage'], $data['placeholder'])
            ;
            $pageKeyMappingTransfer->setIdCmsGlossaryKeyMapping($pageKeyMappingFound->getIdCmsGlossaryKeyMapping());
        }
        $glossaryKey = $this->getQueryContainer()
            ->queryKey($data[CmsGlossaryForm::GLOSSARY_KEY])
            ->findOne()
        ;
        $pageKeyMappingTransfer->setFkGlossaryKey($glossaryKey->getIdGlossaryKey());
        return $pageKeyMappingTransfer;
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
     * @return GlossaryFacade
     */
    private function getGlossaryFacade()
    {
        return $this->getDependencyContainer()
            ->getProvidedDependency(CmsDependencyProvider::FACADE_GLOSSARY)
            ;
    }
    /**
     * @param array $data
     * @param LocaleTransfer $localeTransfer
     */
    private function saveGlossaryKeyPageMapping(array $data, LocaleTransfer $localeTransfer)
    {
        $glossaryFormData = [
            CmsGlossaryForm::GLOSSARY_KEY => $data[CmsGlossaryForm::GLOSSARY_KEY],
            self::LOCALE . $localeTransfer->getIdLocale() => $data[CmsGlossaryForm::TRANSLATION],
        ];
        // @todo saveGlossary functionality changed ...
//        $this->getGlossaryFacade()
//            ->saveGlossaryKeyTranslations($glossaryFormData)
//        ;
        $pageKeyMappingTransfer = $this->createKeyMappingTransfer($data);
        $this->getFacade()
            ->savePageKeyMappingAndTouch($pageKeyMappingTransfer)
        ;
    }
    /**
     * @param SpyCmsPage $pageUrl
     *
     * @return array
     */
    private function findPagePlaceholders(SpyCmsPage $pageUrl)
    {
        $pageUrlArray = $pageUrl->toArray();
        $tempFile = $this->getDependencyContainer()
            ->getTemplateRealPath($pageUrlArray[CmsQueryContainer::TEMPLATE_PATH])
        ;
        $placeholders = $this->findTemplatePlaceholders($tempFile);
        return $placeholders;
    }
    /**
     * @param int $idPage
     * @param LocaleTransfer $localeTransfer
     *
     * @return array
     */
    private function extractGlossaryMapping($idPage, LocaleTransfer $localeTransfer)
    {
        $glossaryQuery = $this->getQueryContainer()
            ->queryGlossaryKeyMappingsWithKeyByPageId($idPage, $localeTransfer->getIdLocale())
        ;
        $glossaryMappingArray = [];
        foreach ($glossaryQuery->find()
                     ->getData() as $keyMapping) {
            $glossaryMappingArray[$keyMapping->getPlaceholder()] = $keyMapping->getIdCmsGlossaryKeyMapping();
        }
        return $glossaryMappingArray;
    }
    /**
     * @param array $forms
     * @param int $idForm
     * @param LocaleTransfer $localeTransfer
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    private function handleAjaxRequest(array $forms, $idForm, LocaleTransfer $localeTransfer)
    {
        if ($forms[$idForm]->isValid()) {
            $data = $forms[$idForm]->getData();
            $this->saveGlossaryKeyPageMapping($data, $localeTransfer);
            return $this->jsonResponse([
                'success' => 'true',
                'data' => $data,
            ]);
        } else {
            return $this->jsonResponse([
                'success' => 'false',
                'errorMessages' => $forms[$idForm]->getErrors()
                    ->__toString(),
            ]);
        }
    }
    /**
     * @param array $glossaryMappingArray
     * @param string $place
     * @param int $idPage
     *
     * @return mixed
     */
    private function createPlaceholderForm(array $glossaryMappingArray, $place, $idPage)
    {
        $idMapping = null;
        if (isset($glossaryMappingArray[$place])) {
            $idMapping = $glossaryMappingArray[$place];
        }
        $form = $this->getDependencyContainer()
            ->createCmsGlossaryForm($idPage, $idMapping, $place, $this->getFacade())
        ;
        $form->handleRequest();
        return $form;
    }

}
