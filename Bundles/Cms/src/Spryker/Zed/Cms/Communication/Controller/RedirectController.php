<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Controller;

use Generated\Shared\Transfer\RedirectTransfer;
use Generated\Shared\Transfer\UrlRedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Url\Persistence\SpyUrl;
use Orm\Zed\Url\Persistence\SpyUrlRedirect;
use Spryker\Zed\Cms\Communication\Form\CmsRedirectForm;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * @method \Spryker\Zed\Cms\Communication\CmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\Cms\Business\CmsFacadeInterface getFacade()
 * @method \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Cms\Persistence\CmsRepositoryInterface getRepository()
 */
class RedirectController extends AbstractController
{
    public const REDIRECT_ADDRESS = '/cms/redirect';
    public const REQUEST_ID_URL = 'id-url';
    public const REQUEST_ID_URL_REDIRECT = 'id-url-redirect';

    public const MESSAGE_REDIRECT_CREATE_SUCCESS = 'Redirect was created successfully.';
    public const MESSAGE_REDIRECT_UPDATE_SUCCESS = 'Redirect was updated successfully.';
    public const MESSAGE_REDIRECT_DELETE_SUCCESS = 'Redirect was deleted successfully.';
    public const MESSAGE_ID_REDIRECT_EXTRACT_ERROR = 'ID redirect URL is not set.';

    /**
     * @return array
     */
    public function indexAction(): array
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
    public function tableAction(): JsonResponse
    {
        $table = $this->getFactory()
            ->createCmsRedirectTable();

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createCmsRedirectFormDataProvider();
        $form = $this->getFactory()
            ->getCmsRedirectForm(
                $dataProvider->getData()
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $sourceUrlTransfer = new UrlTransfer();
            $sourceUrlTransfer
                ->setUrl($data[CmsRedirectForm::FIELD_FROM_URL])
                ->setFkLocale($this->getFactory()->getLocaleFacade()->getCurrentLocale()->getIdLocale());

            $urlRedirectTransfer = new UrlRedirectTransfer();
            $urlRedirectTransfer
                ->fromArray($data, true)
                ->setSource($sourceUrlTransfer);

            $this->getFactory()
                ->getUrlFacade()
                ->createUrlRedirect($urlRedirectTransfer);

            $this->addSuccessMessage(static::MESSAGE_REDIRECT_CREATE_SUCCESS);

            return $this->redirectResponse(static::REDIRECT_ADDRESS);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request)
    {
        $idUrl = $this->castId($request->query->get(self::REQUEST_ID_URL));

        $dataProvider = $this->getFactory()->createCmsRedirectFormDataProvider();
        $form = $this->getFactory()
            ->getCmsRedirectForm(
                $dataProvider->getData($idUrl)
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $sourceUrlTransfer = new UrlTransfer();
            $sourceUrlTransfer
                ->setIdUrl($idUrl)
                ->setUrl($data[CmsRedirectForm::FIELD_FROM_URL])
                ->setFkLocale($this->getFactory()->getLocaleFacade()->getCurrentLocale()->getIdLocale());

            $urlRedirectTransfer = new UrlRedirectTransfer();
            $urlRedirectTransfer
                ->fromArray($data, true)
                ->setSource($sourceUrlTransfer);

            $this->getFactory()
                ->getUrlFacade()
                ->updateUrlRedirect($urlRedirectTransfer);

            $this->addSuccessMessage(static::MESSAGE_REDIRECT_UPDATE_SUCCESS);

            return $this->redirectResponse(static::REDIRECT_ADDRESS);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $url
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function createUrlTransfer(SpyUrl $url, array $data): UrlTransfer
    {
        $urlTransfer = new UrlTransfer();
        $urlTransfer->fromArray($url->toArray(), true);
        $urlTransfer->setUrl($data[CmsRedirectForm::FIELD_FROM_URL]);
        $urlTransfer->setFkRedirect($url->getFkResourceRedirect());
        $urlTransfer->setResourceId($url->getResourceId());
        $urlTransfer->setResourceType($url->getResourceType());

        return $urlTransfer;
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrlRedirect $redirect
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    protected function createRedirectTransfer(SpyUrlRedirect $redirect, array $data): RedirectTransfer
    {
        $redirectTransfer = (new RedirectTransfer())->fromArray($redirect->toArray());
        $redirectTransfer->setToUrl($data[CmsRedirectForm::FIELD_TO_URL]);
        $redirectTransfer->setStatus($data[CmsRedirectForm::FIELD_STATUS]);

        return $redirectTransfer;
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

        $idUrlRedirect = $this->castId($request->request->get(static::REQUEST_ID_URL_REDIRECT));

        if ($idUrlRedirect === 0) {
            $this->addErrorMessage(static::MESSAGE_ID_REDIRECT_EXTRACT_ERROR);

            return $this->redirectResponse('/cms/redirect');
        }

        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer->setIdUrlRedirect($idUrlRedirect);

        $this->getFactory()->getUrlFacade()->deleteUrlRedirect($urlRedirectTransfer);

        $this->addSuccessMessage(static::MESSAGE_REDIRECT_DELETE_SUCCESS);

        return $this->redirectResponse('/cms/redirect');
    }
}
