<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Communication\Controller;

use Generated\Shared\Transfer\PageTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Cms\Business\CmsFacade;
use Spryker\Zed\Cms\CmsDependencyProvider;
use Spryker\Zed\Cms\Communication\CmsCommunicationFactory;
use Spryker\Zed\Cms\Communication\Form\CmsPageForm;
use Spryker\Zed\Cms\Communication\Table\CmsPageTable;
use Spryker\Zed\Url\Business\UrlFacade;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CmsCommunicationFactory getFactory()
 * @method CmsFacade getFacade()
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
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()
            ->createCmsPageTable();

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function addAction(Request $request)
    {
        $form = $this->getFactory()
            ->createCmsPageForm('add');

        $isSynced = $this->getFacade()->syncTemplate(self::CMS_FOLDER_PATH);

        $form->handleRequest($request);

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
     * @param Request $request
     *
     * @return array
     */
    public function editAction(Request $request)
    {
        $idPage = $request->get(CmsPageTable::REQUEST_ID_PAGE);

        $form = $this->getFactory()
            ->createCmsPageForm('update', $idPage);

        $isSynced = $this->getFacade()->syncTemplate(self::CMS_FOLDER_PATH);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $pageTransfer = $this->createPageTransfer($data);
            $pageTransfer = $this->getFacade()->savePage($pageTransfer);
            $this->getFacade()->touchPageActive($pageTransfer);

            if ((int) $data[CmsPageForm::FIELD_CURRENT_TEMPLATE] !== (int) $data[CmsPageForm::FIELD_FK_TEMPLATE]) {
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
     * @return UrlFacade
     */
    private function getUrlFacade()
    {
        return $this->getFactory()
            ->getProvidedDependency(CmsDependencyProvider::FACADE_URL);
    }

    /**
     * @param $data
     *
     * @return PageTransfer
     */
    private function createPageTransfer($data)
    {
        $pageTransfer = new PageTransfer();
        $pageTransfer->fromArray($data, true);

        return $pageTransfer;
    }

    /**
     * @param int $idUrl
     * @param PageTransfer $pageTransfer
     * @param array $data
     *
     * @return UrlTransfer
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
