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
use SprykerFeature\Zed\Cms\Communication\Form\CmsRedirectForm;
use SprykerFeature\Zed\Url\Business\UrlFacade;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CmsDependencyContainer getDependencyContainer()
 * @method CmsFacade getFacade()
 */

class RedirectController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getDependencyContainer()->createCmsRedirectTable();

        return [
            'urls' => $table->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getDependencyContainer()->createCmsRedirectTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @return array
     */
    public function addAction()
    {
        $form = $this->getDependencyContainer()
            ->createCmsRedirectForm('add');

        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();


            $redirectTransfer = $this->getUrlFacade()->createRedirect($data[CmsRedirectForm::TO_URL]);
            $this->getUrlFacade()->touchRedirectActive($redirectTransfer);

            $localeTransfer = $this->getLocaleFacade()->getCurrentLocale();
            $urlTransfer = $this->getUrlFacade()->createRedirectUrl($data[CmsRedirectForm::FROM_URL],$localeTransfer,$redirectTransfer->getIdRedirect());
            $this->getUrlFacade()->touchUrlActive($urlTransfer->getIdUrl());

            return $this->redirectResponse('/cms/redirect/');
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);

    }

    /**
     * @return array
     */
    public function editAction(Request $request)
    {
        $idUrl = $request->get('id_url');

        $form = $this->getDependencyContainer()
            ->createCmsRedirectForm('update', $idUrl);

        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();

            $spyUrl = $this->getQueryContainer()->queryUrlByIdWithRedirect($idUrl)->findOne();
            //todo check the resource type
            if($spyUrl){
                $urlTransfer = (new UrlTransfer())->fromArray($spyUrl->toArray(),true);
                $urlTransfer->setUrl($data[CmsRedirectForm::FROM_URL]);
                $urlTransfer->setFkRedirect($spyUrl->getFkResourceRedirect());
                $urlTransfer->setResourceId($spyUrl->getResourceId());
                $urlTransfer->setResourceType($spyUrl->getResourceType());
                $urlTransfer = $this->getUrlFacade()->saveUrl($urlTransfer);
                $this->getUrlFacade()->touchUrlActive($urlTransfer->getIdUrl());

                $spyRedirect = $this->getQueryContainer()->queryRedirectById($spyUrl->getFkResourceRedirect())->findOne();
                $redirectTransfer = (new RedirectTransfer())->fromArray($spyRedirect->toArray());
                $redirectTransfer->setToUrl($data[CmsRedirectForm::TO_URL]);
                $redirectTransfer = $this->getUrlFacade()->saveRedirect($redirectTransfer);
//                $this->getUrlFacade()->touchRedirectActive($redirectTransfer);

            }

            return $this->redirectResponse('/cms/redirect/');
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
        return $this
            ->getDependencyContainer()
            ->getProvidedDependency(CmsDependencyProvider::URL_BUNDLE)
        ;
    }

    /**
     * @return LocaleFacade
     */
    private function getLocaleFacade()
    {
        return $this
            ->getDependencyContainer()
            ->getProvidedDependency(CmsDependencyProvider::LOCALE_BUNDLE)
            ;
    }

}
