<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Communication\Controller;

use Generated\Shared\Transfer\RedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Cms\CmsDependencyProvider;
use Spryker\Zed\Cms\Communication\Form\CmsRedirectForm;
use Spryker\Zed\Cms\Communication\Table\CmsRedirectTable;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Cms\Communication\CmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\Cms\Business\CmsFacade getFacade()
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
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()
            ->createCmsRedirectTable();

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function addAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createCmsRedirectFormDataProvider();
        $form = $this->getFactory()
            ->createCmsRedirectForm(
                $dataProvider->getData()
            )
            ->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $redirectTransfer = $this->getUrlFacade()
                ->createRedirectAndTouch($data[CmsRedirectForm::FIELD_TO_URL], $data[CmsRedirectForm::FIELD_STATUS]);

            $this->getUrlFacade()
                ->saveRedirectUrlAndTouch($data[CmsRedirectForm::FIELD_FROM_URL], $this->getLocaleFacade()
                    ->getCurrentLocale(), $redirectTransfer->getIdUrlRedirect());

            return $this->redirectResponse(self::REDIRECT_ADDRESS);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function editAction(Request $request)
    {
        $idUrl = $request->query->getInt(CmsRedirectTable::REQUEST_ID_URL);

        $dataProvider = $this->getFactory()->createCmsRedirectFormDataProvider();
        $form = $this->getFactory()
            ->createCmsRedirectForm(
                $dataProvider->getData($idUrl)
            )
            ->handleRequest($request);

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
     * @return \Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface
     */
    private function getUrlFacade()
    {
        return $this->getFactory()
            ->getProvidedDependency(CmsDependencyProvider::FACADE_URL);
    }

    /**
     * @return \Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface
     */
    private function getLocaleFacade()
    {
        return $this->getFactory()
            ->getProvidedDependency(CmsDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $url
     * @param array $data
     *
     * @return self
     */
    private function createUrlTransfer($url, $data)
    {
        $urlTransfer = (new UrlTransfer())->fromArray($url->toArray(), true);
        $urlTransfer->setUrl($data[CmsRedirectForm::FIELD_FROM_URL]);
        $urlTransfer->setFkRedirect($url->getFkResourceRedirect());
        $urlTransfer->setResourceId($url->getResourceId());
        $urlTransfer->setResourceType($url->getResourceType());

        return $urlTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirect
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    private function createRedirectTransfer($redirect, $data)
    {
        $redirectTransfer = (new RedirectTransfer())->fromArray($redirect->toArray());
        $redirectTransfer->setToUrl($data[CmsRedirectForm::FIELD_TO_URL]);
        $redirectTransfer->setStatus($data[CmsRedirectForm::FIELD_STATUS]);

        return $redirectTransfer;
    }

}
