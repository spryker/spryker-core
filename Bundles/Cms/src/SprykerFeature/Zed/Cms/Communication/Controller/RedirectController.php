<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Communication\Controller;

use Generated\Shared\Transfer\RedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Cms\Business\CmsFacade;
use SprykerFeature\Zed\Cms\CmsDependencyProvider;
use SprykerFeature\Zed\Cms\Communication\CmsDependencyContainer;
use SprykerFeature\Zed\Cms\Communication\Form\CmsRedirectForm;
use SprykerFeature\Zed\Cms\Communication\Table\CmsRedirectTable;
use SprykerFeature\Zed\Url\Business\UrlFacade;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CmsDependencyContainer getDependencyContainer()
 * @method CmsFacade getFacade()
 */
class RedirectController extends AbstractController
{

    const REDIRECT_ADDRESS = '/cms/';

    /**
     * @return array
     */
    public function addAction()
    {
        $form = $this->getDependencyContainer()
            ->createCmsRedirectForm('add')
        ;

        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();

            //@todo new CMS_FACADE_API
            $redirectTransfer = $this->getUrlFacade()
                ->createRedirect($data[CmsRedirectForm::TO_URL])
            ;
            $this->getUrlFacade()
                ->touchRedirectActive($redirectTransfer)
            ;

            $localeTransfer = $this->getLocaleFacade()
                ->getCurrentLocale()
            ;
            $urlTransfer = $this->getUrlFacade()
                ->createRedirectUrl($data[CmsRedirectForm::FROM_URL], $localeTransfer, $redirectTransfer->getIdRedirect())
            ;
            $this->getUrlFacade()
                ->touchUrlActive($urlTransfer->getIdUrl())
            ;

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

        $form = $this->getDependencyContainer()
            ->createCmsRedirectForm('update', $idUrl)
        ;

        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();

            //@todo new CMS_FACADE_API
            $url = $this->getQueryContainer()
                ->queryUrlByIdWithRedirect($idUrl)
                ->findOne()
            ;
            // @todo check the resource type
            if ($url) {
                $urlTransfer = (new UrlTransfer())->fromArray($url->toArray(), true);
                $urlTransfer->setUrl($data[CmsRedirectForm::FROM_URL]);
                $urlTransfer->setFkRedirect($url->getFkResourceRedirect());
                $urlTransfer->setResourceId($url->getResourceId());
                $urlTransfer->setResourceType($url->getResourceType());
                $urlTransfer = $this->getUrlFacade()
                    ->saveUrl($urlTransfer)
                ;
                $this->getUrlFacade()
                    ->touchUrlActive($urlTransfer->getIdUrl())
                ;

                $redirect = $this->getQueryContainer()
                    ->queryRedirectById($url->getFkResourceRedirect())
                    ->findOne()
                ;
                $redirectTransfer = (new RedirectTransfer())->fromArray($redirect->toArray());
                $redirectTransfer->setToUrl($data[CmsRedirectForm::TO_URL]);
                $redirectTransfer = $this->getUrlFacade()
                    ->saveRedirect($redirectTransfer)
                ;
                $this->getUrlFacade()
                    ->touchRedirectActive($redirectTransfer)
                ;
            }

            return $this->redirectResponse(self::REDIRECT_ADDRESS);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return UrlFacade
     */
    private function getUrlFacade()
    {
        return $this->getDependencyContainer()
            ->getProvidedDependency(CmsDependencyProvider::FACADE_URL)
            ;
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

}
