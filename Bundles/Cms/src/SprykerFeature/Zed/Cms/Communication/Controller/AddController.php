<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Communication\Controller;



use Generated\Shared\Transfer\PageTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Cms\Business\CmsFacade;
use SprykerFeature\Zed\Cms\CmsDependencyProvider;
use SprykerFeature\Zed\Cms\Communication\Form\CmsForm;
use SprykerFeature\Zed\Url\Business\UrlFacade;

/**
 * @method CmsDependencyContainer getDependencyContainer()
 * @method CmsFacade getFacade()
 */

class AddController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $form = $this->getDependencyContainer()
            ->createCmsForm('add');

        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();

            $pageTransfer = new PageTransfer();
            $pageTransfer->fromArray($data, true);

//            $this->getUrlFacade()->saveRedirect()

            $pageTransfer = $this->getFacade()->savePage($pageTransfer);

            $this->getFacade()
                ->createPageUrl($pageTransfer,$data[CmsForm::URL])
            ;

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

}
