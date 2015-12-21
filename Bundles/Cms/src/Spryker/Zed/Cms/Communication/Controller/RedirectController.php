<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Communication\Controller;

use Generated\Shared\Transfer\RedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Cms\Business\CmsFacade;
use Spryker\Zed\Cms\CmsDependencyProvider;
use Spryker\Zed\Cms\Communication\CmsCommunicationFactory;
use Spryker\Zed\Cms\Communication\Form\CmsRedirectForm;
use Spryker\Zed\Cms\Communication\Table\CmsRedirectTable;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CmsCommunicationFactory getFactory()
 * @method CmsFacade getFacade()
 */
class RedirectController extends AbstractController
{

    const REDIRECT_ADDRESS = '/cms/redirect/';

    /**
     * @return array
     */
    public function indexAction()
    {
        $redirectTable = $this->getFactory()
            ->createCmsRedirectTable();

        return [
            'redirects' => $redirectTable->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()
            ->createCmsRedirectTable();

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @return array
     */
    public function addAction()
    {
        $form = $this->getFactory()
            ->createCmsRedirectForm('add');

        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();

            $redirectTransfer = $this->getUrlFacade()
                ->createRedirectAndTouch($data[CmsRedirectForm::TO_URL], $data[CmsRedirectForm::STATUS]);

            $this->getUrlFacade()
                ->saveRedirectUrlAndTouch($data[CmsRedirectForm::FROM_URL], $this->getLocaleFacade()
                    ->getCurrentLocale(), $redirectTransfer->getIdUrlRedirect());

            return $this->redirectResponse(self::REDIRECT_ADDRESS);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function editAction(Request $request)
    {
        $idUrl = $request->get(CmsRedirectTable::REQUEST_ID_URL);

        $form = $this->getFactory()
            ->createCmsRedirectForm('update', $idUrl);

        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();
            $url = $this->getQueryContainer()->queryUrlByIdWithRedirect($idUrl)->findOne();

            if ($url) {
                $urlTransfer = $this->createUrlTransfer($url, $data);
                $this->getUrlFacade()->saveUrlAndTouch($urlTransfer);

                $redirect = $this->getQueryContainer()
                    ->queryRedirectById($url->getFkResourceRedirect())
                    ->findOne();
                $redirectTransfer = $this->createRedirectTransfer($redirect, $data);

                $this->getUrlFacade()->saveRedirectAndTouch($redirectTransfer);
            }

            return $this->redirectResponse(self::REDIRECT_ADDRESS);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return CmsToUrlInterface
     */
    private function getUrlFacade()
    {
        return $this->getFactory()
            ->getProvidedDependency(CmsDependencyProvider::FACADE_URL);
    }

    /**
     * @return CmsToLocaleInterface
     */
    private function getLocaleFacade()
    {
        return $this->getFactory()
            ->getProvidedDependency(CmsDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @param $url
     * @param $data
     *
     * @return self
     */
    private function createUrlTransfer($url, $data)
    {
        $urlTransfer = (new UrlTransfer())->fromArray($url->toArray(), true);
        $urlTransfer->setUrl($data[CmsRedirectForm::FROM_URL]);
        $urlTransfer->setFkRedirect($url->getFkResourceRedirect());
        $urlTransfer->setResourceId($url->getResourceId());
        $urlTransfer->setResourceType($url->getResourceType());

        return $urlTransfer;
    }

    /**
     * @param $redirect
     * @param $data
     *
     * @return self
     */
    private function createRedirectTransfer($redirect, $data)
    {
        $redirectTransfer = (new RedirectTransfer())->fromArray($redirect->toArray());
        $redirectTransfer->setToUrl($data[CmsRedirectForm::TO_URL]);
        $redirectTransfer->setStatus($data[CmsRedirectForm::STATUS]);

        return $redirectTransfer;
    }

}
