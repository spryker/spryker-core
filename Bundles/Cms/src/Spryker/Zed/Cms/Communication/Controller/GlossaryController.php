<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Controller;

use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageKeyMappingTransfer;
use Generated\Shared\Transfer\PageTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Spryker\Shared\Cms\CmsConstants;
use Spryker\Zed\Cms\Business\Exception\MissingPageException;
use Spryker\Zed\Cms\Communication\Form\CmsGlossaryForm;
use Spryker\Zed\Cms\Communication\Table\CmsGlossaryTable;
use Spryker\Zed\Cms\Communication\Table\CmsTableConstants;
use Spryker\Zed\Cms\Persistence\CmsQueryContainer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * @method \Spryker\Zed\Cms\Communication\CmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Cms\Persistence\CmsRepositoryInterface getRepository()
 * @method \Spryker\Zed\Cms\Business\CmsFacadeInterface getFacade()
 */
class GlossaryController extends AbstractController
{
    /**
     * @var string
     */
    protected const REDIRECT_ADDRESS = '/cms/glossary';

    /**
     * @var int
     */
    protected const SEARCH_LIMIT = 10;

    /**
     * @var string
     */
    protected const ID_FORM = 'id-form';

    /**
     * @var string
     */
    protected const TYPE = 'type';

    /**
     * @var string
     */
    protected $glossaryKeyName = '';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|array
     */
    public function indexAction(Request $request)
    {
        $idPage = $this->castId($request->get(CmsTableConstants::REQUEST_ID_PAGE));
        $idForm = (int)$request->get(static::ID_FORM);
        $type = CmsConstants::RESOURCE_TYPE_PAGE;

        /** @var \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity */
        $cmsPageEntity = $this->findCmsPageById($idPage);
        $localeTransfer = $this->getLocaleTransfer($cmsPageEntity);

        $fkLocale = $this->getLocaleByCmsPage($cmsPageEntity);

        $title = $cmsPageEntity->getUrl();

        $placeholders = $this->findPagePlaceholders($cmsPageEntity);
        $glossaryMappingArray = $this->extractGlossaryMapping($idPage, $localeTransfer);

        $forms = [];
        $formViews = [];

        foreach ($placeholders as $place) {
            $form = $this->createPlaceholderForm($request, $glossaryMappingArray, $place, $idPage, $fkLocale);
            $forms[] = $form;
            $formViews[] = $form->createView();
        }

        if ($idForm !== null && $request->isXmlHttpRequest()) {
            return $this->handleAjaxRequest($forms, $idForm, $localeTransfer);
        }

        return [
            'idPage' => $idPage,
            'title' => $title,
            'type' => $type,
            'forms' => $formViews,
            'localeTransfer' => $localeTransfer,
        ];
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPage
     *
     * @return int|null
     */
    protected function getLocaleByCmsPage(SpyCmsPage $cmsPage): ?int
    {
        $fkLocale = null;
        $url = $cmsPage->getSpyUrls()->getFirst();

        if ($url) {
            $fkLocale = $url->getFkLocale();
        }

        return $fkLocale;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request): RedirectResponse
    {
        if (!$request->isMethod(Request::METHOD_DELETE)) {
            throw new MethodNotAllowedHttpException([Request::METHOD_DELETE], 'This action requires a DELETE request.');
        }

        $idMapping = $this->castId($request->request->get(CmsGlossaryTable::REQUEST_ID_MAPPING));
        $idPage = $this->castId($request->request->get(CmsTableConstants::REQUEST_ID_PAGE));

        $mappingGlossary = $this->getQueryContainer()
            ->queryGlossaryKeyMappingById($idMapping)
            ->findOne();

        $pageTransfer = (new PageTransfer())->setIdCmsPage($idPage);
        $this->getFacade()
            ->deletePageKeyMapping($pageTransfer, $mappingGlossary->getPlaceholder());

        $redirectUrl = static::REDIRECT_ADDRESS . '?' . http_build_query([CmsTableConstants::REQUEST_ID_PAGE => $idPage]);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param string $tempFile
     *
     * @return array
     */
    protected function findTemplatePlaceholders(string $tempFile): array
    {
        $placeholderMap = [];
        $placeholderPattern = $this->getFactory()->getConfig()->getPlaceholderPattern();
        $placeholderValuePattern = $this->getFactory()->getConfig()->getPlaceholderValuePattern();

        if (file_exists($tempFile)) {
            /** @var string $fileContent */
            $fileContent = file_get_contents($tempFile);

            preg_match_all($placeholderPattern, $fileContent, $cmsPlaceholderLine);
            preg_match_all($placeholderValuePattern, implode(' ', $cmsPlaceholderLine[0]), $placeholderMap);

            return $placeholderMap[1];
        }

        return $placeholderMap;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function searchAction(Request $request): JsonResponse
    {
        /** @var string|null $value */
        $value = $request->query->get('value');
        /** @var string|null $key */
        $key = $request->query->get('key');
        $localeId = $this->castId($request->query->get('localeId'));

        $searchedItems = $this->searchGlossaryKeysAndTranslations($value, $key, $localeId);

        $result = [];
        /** @var \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation $trans */
        foreach ($searchedItems as $trans) {
            $result[] = [
                'key' => $trans->getLabel(),
                'value' => $trans->getValue(),
            ];
        }

        return $this->jsonResponse($result);
    }

    /**
     * @param string|null $value
     * @param string|null $key
     * @param int|null $localeId
     *
     * @return array<\Orm\Zed\Glossary\Persistence\SpyGlossaryKey|\Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation>
     */
    protected function searchGlossaryKeysAndTranslations(?string $value, ?string $key, ?int $localeId)
    {
        $searchedItems = [];
        if ($value !== null) {
            /** @var array<\Orm\Zed\Glossary\Persistence\SpyGlossaryKey|\Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation> $searchedItems */
            $searchedItems = $this->getQueryContainer()
                ->queryTranslationWithKeyByValue($value)
                ->limit(static::SEARCH_LIMIT)
                ->find();

            return $searchedItems;
        }
        if ($key !== null) {
            /** @var array<\Orm\Zed\Glossary\Persistence\SpyGlossaryKey|\Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation> $searchedItems */
            $searchedItems = $this->getQueryContainer()
                ->queryKeyWithTranslationByKeyAndLocale($key, $localeId)
                ->limit(static::SEARCH_LIMIT)
                ->find();
        }

        return $searchedItems;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return \Generated\Shared\Transfer\PageKeyMappingTransfer
     */
    protected function createKeyMappingTransfer(array $data): PageKeyMappingTransfer
    {
        $pageKeyMappingTransfer = (new PageKeyMappingTransfer())->fromArray($data, true);
        $hasPageMapping = $this->getFacade()->hasPagePlaceholderMapping($data['fkPage'], $data['placeholder']);
        if ($hasPageMapping) {
            $pageKeyMappingFound = $this->getFacade()
                ->getPagePlaceholderMapping($data['fkPage'], $data['placeholder']);
            $pageKeyMappingTransfer->setIdCmsGlossaryKeyMapping($pageKeyMappingFound->getIdCmsGlossaryKeyMapping());
        }
        $glossaryKey = $this->getQueryContainer()
            ->queryKey($this->glossaryKeyName)
            ->findOne();
        $pageKeyMappingTransfer->setFkGlossaryKey($glossaryKey->getIdGlossaryKey());

        return $pageKeyMappingTransfer;
    }

    /**
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function saveGlossaryKeyPageMapping(array $data, LocaleTransfer $localeTransfer): void
    {
        $keyTranslationTransfer = $this->createKeyTranslationTransfer($data, $localeTransfer);
        $this->getFactory()->getGlossaryFacade()
            ->saveGlossaryKeyTranslations($keyTranslationTransfer);
        $pageKeyMappingTransfer = $this->createKeyMappingTransfer($data);
        $this->getFacade()
            ->savePageKeyMappingAndTouch($pageKeyMappingTransfer, $localeTransfer);
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $pageUrl
     *
     * @return array
     */
    protected function findPagePlaceholders(SpyCmsPage $pageUrl): array
    {
        $pageUrlArray = $pageUrl->toArray();
        $tempFiles = $this->getFactory()
            ->getTemplateRealPaths($pageUrlArray[CmsQueryContainer::TEMPLATE_PATH]);

        /* Added for keeping BC */
        if (!is_array($tempFiles)) {
            $tempFiles = [$tempFiles];
        }

        $placeholders = [];
        foreach ($tempFiles as $tempFile) {
            if (!file_exists($tempFile)) {
                continue;
            }

            $placeholders = $this->findTemplatePlaceholders($tempFile);
        }

        return $placeholders;
    }

    /**
     * @param int|null $idPage
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    protected function extractGlossaryMapping(?int $idPage, LocaleTransfer $localeTransfer): array
    {
        $glossaryQuery = $this->getQueryContainer()
            ->queryGlossaryKeyMappingsWithKeyByPageId($idPage, $localeTransfer->getIdLocale());
        $glossaryMappingArray = [];

        /** @var array<\Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMapping> $keyMappings */
        $keyMappings = $glossaryQuery->find()
            ->getData();
        foreach ($keyMappings as $keyMapping) {
            $glossaryMappingArray[$keyMapping->getPlaceholder()] = $keyMapping->getIdCmsGlossaryKeyMapping();
        }

        return $glossaryMappingArray;
    }

    /**
     * @param array<\Symfony\Component\Form\FormInterface> $forms
     * @param int $idForm
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function handleAjaxRequest(array $forms, int $idForm, LocaleTransfer $localeTransfer): JsonResponse
    {
        if ($forms[$idForm]->isSubmitted() && $forms[$idForm]->isValid()) {
            $data = $forms[$idForm]->getData();
            $this->saveGlossaryKeyPageMapping($data, $localeTransfer);

            return $this->jsonResponse([
                'success' => 'true',
                'glossaryKeyName' => $this->glossaryKeyName,
                'data' => $data,
            ]);
        }

        return $this->jsonResponse([
            'success' => 'false',
            'errorMessages' => $forms[$idForm]->getErrors()
                ->__toString(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $glossaryMappingArray
     * @param string|null $placeholder
     * @param int|null $idPage
     * @param int|null $fkLocale
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createPlaceholderForm(Request $request, array $glossaryMappingArray, ?string $placeholder, ?int $idPage, ?int $fkLocale): FormInterface
    {
        $idMapping = null;
        if (isset($glossaryMappingArray[$placeholder])) {
            $idMapping = $glossaryMappingArray[$placeholder];
        }

        $dataProvider = $this->getFactory()->createCmsGlossaryFormDataProvider();
        $form = $this->getFactory()
            ->getCmsGlossaryForm(
                $dataProvider->getData($idPage, $idMapping, $placeholder, $fkLocale),
            )
            ->handleRequest($request);

        return $form;
    }

    /**
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\KeyTranslationTransfer
     */
    protected function createKeyTranslationTransfer(array $data, LocaleTransfer $localeTransfer): KeyTranslationTransfer
    {
        $this->glossaryKeyName = $data[CmsGlossaryForm::FIELD_GLOSSARY_KEY];

        if ($this->glossaryKeyName === null) {
            $this->glossaryKeyName = $this->getFacade()
                ->generateGlossaryKeyName($data[CmsGlossaryForm::FIELD_TEMPLATE_NAME], $data[CmsGlossaryForm::FIELD_PLACEHOLDER]);
        }

        $keyTranslationTransfer = new KeyTranslationTransfer();
        $keyTranslationTransfer->setGlossaryKey($this->glossaryKeyName);

        $keyTranslationTransfer->setLocales([
            $localeTransfer->getLocaleName() => $data[CmsGlossaryForm::FIELD_TRANSLATION],
        ]);

        return $keyTranslationTransfer;
    }

    /**
     * @param int $idPage
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage|\Orm\Zed\Url\Persistence\SpyUrl
     */
    protected function findCmsPageById(int $idPage)
    {
        $cmsPage = $this->getQueryContainer()
            ->queryPageWithTemplatesAndUrlByIdPage($idPage)
            ->findOne();

        if ($cmsPage === null) {
            throw new MissingPageException(
                sprintf('Page with id %s not found', $idPage),
            );
        }

        return $cmsPage;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getLocaleTransfer(SpyCmsPage $cmsPageEntity): LocaleTransfer
    {
        $localeTransfer = $this->getFactory()->getLocaleFacade()->getCurrentLocale();
        $url = $cmsPageEntity->getSpyUrls()->getFirst();

        if ($url) {
            $localeTransfer = new LocaleTransfer();
            $localeTransfer->fromArray($url->getSpyLocale()->toArray());
        }

        return $localeTransfer;
    }
}
