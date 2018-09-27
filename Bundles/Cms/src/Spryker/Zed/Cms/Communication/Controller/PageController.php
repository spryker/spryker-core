<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Controller;

use Generated\Shared\Transfer\CmsPageLocalizedAttributesTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Spryker\Zed\Cms\Communication\Form\CmsPageForm;
use Spryker\Zed\Cms\Communication\Table\CmsPageTable;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Use CMS page creation from CmsGui bundle.
 *
 * @method \Spryker\Zed\Cms\Communication\CmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\Cms\Business\CmsFacadeInterface getFacade()
 * @method \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface getQueryContainer()
 */
class PageController extends AbstractController
{
    public const REDIRECT_ADDRESS = '/cms/glossary';
    public const CMS_FOLDER_PATH = '@Cms/template/';

    /**
     * @return array
     */
    public function indexAction()
    {
        $pageTable = $this->getFactory()
            ->createCmsPageTable();

        return [
            'pages' => $pageTable->render(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()
            ->createCmsPageTable();

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function addAction(Request $request)
    {
        $isSynced = $this->getFacade()->syncTemplate(self::CMS_FOLDER_PATH);

        $dataProvider = $this->getFactory()->createCmsPageFormDataProvider();
        $form = $this->getFactory()
            ->createCmsPageForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $pageTransfer = $this->createPageTransfer($data);

            $this->getFacade()
                ->savePageUrlAndTouch($pageTransfer);

            $redirectUrl = self::REDIRECT_ADDRESS . '?' . CmsPageTable::REQUEST_ID_PAGE . '=' . $pageTransfer->getIdCmsPage();

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'isSynced' => $isSynced,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function editAction(Request $request)
    {
        $idPage = $this->castId($request->query->get(CmsPageTable::REQUEST_ID_PAGE));
        $isSynced = $this->getFacade()->syncTemplate(self::CMS_FOLDER_PATH);

        $dataProvider = $this->getFactory()->createCmsPageFormDataProvider();
        $data = $dataProvider->getData($idPage);

        $form = $this
            ->getFactory()
            ->createCmsPageForm(
                $data,
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $pageTransfer = $this->createPageTransfer($data);
            $pageTransfer = $this->getFacade()->savePage($pageTransfer);
            $localeTransfer = $this->getLocaleTransfer($idPage);

            $this->createTranslationsForLocale($idPage, $localeTransfer);

            $this->getFacade()->touchPageActive($pageTransfer, $localeTransfer);

            if ((int)$data[CmsPageForm::FIELD_CURRENT_TEMPLATE] !== (int)$data[CmsPageForm::FIELD_FK_TEMPLATE]) {
                $this->getFacade()->deleteGlossaryKeysByIdPage($idPage);
            }

            $urlTransfer = $this->createUrlTransfer($data['id_url'], $pageTransfer, $data);
            $this->getFactory()->getUrlFacade()->updateUrl($urlTransfer);

            $redirectUrl = self::REDIRECT_ADDRESS . '?' . CmsPageTable::REQUEST_ID_PAGE . '=' . $pageTransfer->getIdCmsPage();

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'isSynced' => $isSynced,
            'idCmsPage' => $idPage,
            'isActive' => $data[CmsPageForm::FIELD_IS_ACTIVE],
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $idPage = $this->castId($request->query->get(CmsPageTable::REQUEST_ID_PAGE));

        $this->getFacade()->deletePageById($idPage);
        $this->addSuccessMessage('CMS Page deleted successfully.');

        return $this->redirectResponse('/cms/page');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateAction(Request $request)
    {
        $idPage = $this->castId($request->query->get(CmsPageTable::REQUEST_ID_PAGE));

        $this->updatePageState($idPage, true);

        return $this->redirectResponse($request->headers->get('referer'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivateAction(Request $request)
    {
        $idPage = $this->castId($request->query->get(CmsPageTable::REQUEST_ID_PAGE));

        $this->updatePageState($idPage, false);

        return $this->redirectResponse($request->headers->get('referer'));
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    protected function createPageTransfer(array $data)
    {
        $urlTransfer = new UrlTransfer();
        $urlTransfer->fromArray($data, true);
        unset($data[CmsPageForm::FIELD_URL]);

        $pageTransfer = new PageTransfer();
        $pageTransfer->addLocalizedAttribute($this->createCmsPageLocalizedAttributesTransfer($data));
        unset($data[CmsPageForm::FIELD_LOCALIZED_ATTRIBUTES]);

        $pageTransfer->fromArray($data, true);

        $pageTransfer->setUrl($urlTransfer);

        return $pageTransfer;
    }

    /**
     * @param int $idUrl
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function createUrlTransfer($idUrl, $pageTransfer, array $data)
    {
        $url = $this->getQueryContainer()
            ->queryUrlById($idUrl)
            ->findOne();

        $urlTransfer = new UrlTransfer();

        $urlTransfer = $urlTransfer->fromArray($url->toArray(), true);
        $urlTransfer->setFkPage($pageTransfer->getIdCmsPage());
        $urlTransfer->setResourceId($url->getResourceId());
        $urlTransfer->setResourceType($url->getResourceType());
        $urlTransfer->setUrl($data['url']);

        return $urlTransfer;
    }

    /**
     * @param int $idPage
     * @param bool $isActive
     *
     * @return void
     */
    protected function updatePageState($idPage, $isActive)
    {
        $dataProvider = $this->getFactory()->createCmsPageFormDataProvider();
        $data = $dataProvider->getData($idPage);
        $data[CmsPageForm::FIELD_IS_ACTIVE] = $isActive;

        $pageTransfer = $this->createPageTransfer($data);

        $localeTransfer = $this->getLocaleTransfer($idPage);

        $this->getFacade()->savePage($pageTransfer);
        $this->getFacade()->touchPageActive($pageTransfer, $localeTransfer);
    }

    /**
     * @param int $idPage
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer|null
     */
    protected function getLocaleTransfer($idPage)
    {
        $localeTransfer = null;
        $cmsPageEntity = $this->getQueryContainer()->queryPageById($idPage)->findOne();

        if ($cmsPageEntity) {
            $localeEntity = $cmsPageEntity->getSpyUrls()->getFirst()->getSpyLocale();
            $localeTransfer = new LocaleTransfer();
            $localeTransfer->fromArray($localeEntity->toArray());

            return $localeTransfer;
        }

        return $localeTransfer;
    }

    /**
     * Finds all glossary keys and creates missing translations as empty to be able to touch the page.
     *
     * @param int $idPage
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function createTranslationsForLocale($idPage, LocaleTransfer $localeTransfer)
    {
        $glossaryKeyCollection = $this->getQueryContainer()->queryGlossaryKeyMappingsByPageId($idPage)
            ->innerJoinGlossaryKey()
            ->withColumn(SpyGlossaryKeyTableMap::COL_KEY, 'glossaryKey')
            ->find();
        foreach ($glossaryKeyCollection as $glossaryKeyEntity) {
            $key = $glossaryKeyEntity->getGlossaryKey()->getKey();
            $translation = $this->getQueryContainer()->queryKeyWithTranslationByKeyAndLocale($key, $localeTransfer->getIdLocale())->findOne();
            if ($translation !== null) {
                continue;
            }

            $this->getFactory()->getGlossaryFacade()->createAndTouchTranslation($key, $localeTransfer, '');
        }
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CmsPageLocalizedAttributesTransfer
     */
    protected function createCmsPageLocalizedAttributesTransfer(array $data)
    {
        $cmsPageLocalizedAttributesTransfer = new CmsPageLocalizedAttributesTransfer();
        $cmsPageLocalizedAttributesTransfer
            ->fromArray($data[CmsPageForm::FIELD_LOCALIZED_ATTRIBUTES], true)
            ->setFkLocale($data[CmsPageForm::FIELD_FK_LOCALE]);

        return $cmsPageLocalizedAttributesTransfer;
    }
}
