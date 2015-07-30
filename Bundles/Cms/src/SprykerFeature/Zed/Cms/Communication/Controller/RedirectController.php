<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Communication\Controller;



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
            $localeTransfer = $this->getLocaleFacade()->getCurrentLocale();
            $this->getUrlFacade()->createRedirectUrl($data[CmsRedirectForm::FROM_URL],$localeTransfer,$redirectTransfer->getIdRedirect());

            return $this->redirectResponse('/cms/');
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
        $idRedirect = $request->get('id_redirect');

        echo $idRedirect;
        exit;
        $form = $this->getDependencyContainer()
            ->createCmsRedirectForm('update');

        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();


//            $redirectTransfer = $this->getUrlFacade()->createRedirect($data[CmsRedirectForm::TO_URL]);
//            $localeTransfer = $this->getLocaleFacade()->getCurrentLocale();
//            $this->getUrlFacade()->createRedirectUrl($data[CmsRedirectForm::FROM_URL],$localeTransfer,$redirectTransfer->getIdRedirect());

            return $this->redirectResponse('/cms/');
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
