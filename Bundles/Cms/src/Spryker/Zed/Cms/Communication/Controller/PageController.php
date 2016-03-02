<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Controller;

use Generated\Shared\Transfer\PageTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Cms\CmsDependencyProvider;
use Spryker\Zed\Cms\Communication\Form\CmsPageForm;
use Spryker\Zed\Cms\Communication\Table\CmsPageTable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Cms\Communication\CmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\Cms\Business\CmsFacade getFacade()
 */
class PageController extends AbstractController
{

    const REDIRECT_ADDRESS = '/cms/glossary/';
    const CMS_FOLDER_PATH = '@Cms/template/';

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
     * @return array
     */
    public function addAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createCmsPageFormDataProvider();
        $form = $this
            ->getFactory()
            ->createCmsPageForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        $isSynced = $this->getFacade()->syncTemplate(self::CMS_FOLDER_PATH);

        if ($form->isValid()) {
            $data = $form->getData();
            $pageTransfer = $this->createPageTransfer($data);

            $this->getFacade()
                ->savePageUrlAndTouch($pageTransfer, $data[CmsPageForm::FIELD_URL]);
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
     * @return array
     */
    public function editAction(Request $request)
    {
        $idPage = $this->castId($request->query->get(CmsPageTable::REQUEST_ID_PAGE));

        $isSynced = $this->getFacade()->syncTemplate(self::CMS_FOLDER_PATH);

        $dataProvider = $this->getFactory()->createCmsPageFormDataProvider();
        $form = $this
            ->getFactory()
            ->createCmsPageForm(
                $dataProvider->getData($idPage),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $pageTransfer = $this->createPageTransfer($data);
            $pageTransfer = $this->getFacade()->savePage($pageTransfer);
            $this->getFacade()->touchPageActive($pageTransfer);

            if ((int)$data[CmsPageForm::FIELD_CURRENT_TEMPLATE] !== (int)$data[CmsPageForm::FIELD_FK_TEMPLATE]) {
                $this->getFacade()->deleteGlossaryKeysByIdPage($idPage);
            }

            $urlTransfer = $this->createUrlTransfer($data['id_url'], $pageTransfer, $data);
            $this->getUrlFacade()->saveUrlAndTouch($urlTransfer);

            $redirectUrl = self::REDIRECT_ADDRESS . '?' . CmsPageTable::REQUEST_ID_PAGE . '=' . $pageTransfer->getIdCmsPage();

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'isSynced' => $isSynced,
        ]);
    }

    /**
     * @return \Spryker\Zed\Url\Business\UrlFacade
     */
    private function getUrlFacade()
    {
        return $this->getFactory()
            ->getProvidedDependency(CmsDependencyProvider::FACADE_URL);
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    private function createPageTransfer($data)
    {
        $pageTransfer = new PageTransfer();
        $pageTransfer->fromArray($data, true);

        return $pageTransfer;
    }

    /**
     * @param int $idUrl
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    private function createUrlTransfer($idUrl, $pageTransfer, array $data)
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

}
