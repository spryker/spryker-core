<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Communication\Controller;



use Generated\Shared\Transfer\PageTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Cms\Business\CmsFacade;
use SprykerFeature\Zed\Cms\Communication\Form\CmsPageForm;
use Symfony\Component\HttpFoundation\Request;


/**
 * @method CmsDependencyContainer getDependencyContainer()
 * @method CmsFacade getFacade()
 */

class GlossaryController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idPage = $request->get('id_page');

        $table = $this->getDependencyContainer()->createCmsGlossaryTable($idPage);

        return [
            'keyMaps' => $table->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction(Request $request)
    {
        $idPage = $request->get('id_page');

        $table = $this->getDependencyContainer()->createCmsGlossaryTable($idPage);

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
            ->createCmsPageForm('add');

        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();

            $pageTransfer = new PageTransfer();
            $pageTransfer->fromArray($data, true);

            $pageTransfer = $this->getFacade()->savePage($pageTransfer);

            $this->getFacade()
                ->createPageUrl($pageTransfer,$data[CmsPageForm::URL])
            ;

            return $this->redirectResponse('/cms/');
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);

    }
}
