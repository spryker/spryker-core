<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Communication\Controller;

use Generated\Shared\Transfer\PageTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Cms\Business\CmsFacade;
use SprykerFeature\Zed\Cms\Communication\Form\CmsBlockForm;
use SprykerFeature\Zed\Cms\Communication\Form\CmsPageForm;
use SprykerFeature\Zed\Cms\Communication\Table\CmsPageTable;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CmsDependencyContainer getDependencyContainer()
 * @method CmsFacade getFacade()
 */
class BlockController extends AbstractController
{

    const REDIRECT_ADDRESS = '/cms/glossary/';

    /**
     * @return array
     */
    public function indexAction()
    {
        $blockTable = $this->getDependencyContainer()
            ->createCmsBlockTable()
        ;

        return [
            'blocks' => $blockTable->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getDependencyContainer()
            ->createCmsBlockTable()
        ;

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @return array
     */
    public function addAction()
    {
        $form = $this->getDependencyContainer()
            ->createCmsBlockForm('add')
        ;

        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();
            $pageTransfer = $this->createPageTransfer($data);

            $this->getFacade()
                ->savePageBlockAndTouch($pageTransfer, $data[CmsBlockForm::BLOCK])
            ;
            $redirectUrl = self::REDIRECT_ADDRESS . '?' . CmsPageTable::REQUEST_ID_PAGE . '=' . $pageTransfer->getIdCmsPage();

            return $this->redirectResponse($redirectUrl);
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
        $idPage = $request->get(CmsPageTable::REQUEST_ID_PAGE);

        $form = $this->getDependencyContainer()
            ->createCmsBlockForm('update', $idPage)
        ;

        $form->handleRequest();
        if ($form->isValid()) {
            $data = $form->getData();

            $pageTransfer = $this->createPageTransfer($data);
            $pageTransfer = $this->getFacade()
                ->savePage($pageTransfer)
            ;

            $this->updatePageAndBlock($data, $idPage, $pageTransfer);

            $redirectUrl = self::REDIRECT_ADDRESS . '?' . CmsPageTable::REQUEST_ID_PAGE . '=' . $pageTransfer->getIdCmsPage();

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param array $data
     *
     * @return PageTransfer
     */
    private function createPageTransfer(array $data)
    {
        $pageTransfer = new PageTransfer();
        $pageTransfer->fromArray($data, true);

        return $pageTransfer;
    }

    /**
     * @param array $data
     * @param int $idPage
     * @param PageTransfer $pageTransfer
     */
    protected function updatePageAndBlock(array $data, $idPage, $pageTransfer)
    {
        if (intval($data[CmsPageForm::CURRENT_TEMPLATE]) !== intval($data[CmsPageForm::FK_TEMPLATE])) {
            $this->getFacade()
                ->deleteGlossaryKeysByIdPage($idPage)
            ;
        }

        $this->getFacade()
            ->savePageBlockAndTouch($pageTransfer, $data[CmsBlockForm::BLOCK])
        ;
    }
}
