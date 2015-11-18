<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Communication\Controller;

use Functional\SprykerFeature\Zed\ProductOption\Mock\LocaleFacade;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\PageTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Cms\Business\CmsFacade;
use SprykerFeature\Zed\Cms\Communication\CmsDependencyContainer;
use SprykerFeature\Zed\Cms\Communication\Form\CmsBlockForm;
use SprykerFeature\Zed\Cms\Communication\Form\CmsPageForm;
use SprykerFeature\Zed\Cms\Communication\Table\CmsBlockTable;
use SprykerFeature\Zed\Cms\Communication\Table\CmsPageTable;
use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CmsDependencyContainer getDependencyContainer()
 * @method CmsFacade getFacade()
 * @method CmsQueryContainer getQueryContainer()
 */
class BlockController extends AbstractController
{

    const REDIRECT_ADDRESS = '/cms/glossary/';
    const CMS_FOLDER_PATH = '@Cms/template/';

    /**
     * @return array
     */
    public function indexAction()
    {
        $blockTable = $this->getDependencyContainer()
            ->createCmsBlockTable($this->getCurrentLocaleId())
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
            ->createCmsBlockTable($this->getCurrentLocaleId())
        ;

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @return array|RedirectResponse
     */
    public function addAction()
    {
        $form = $this->getDependencyContainer()->createCmsBlockForm('add');
        $isSynced = $this->getFacade()->syncTemplate(self::CMS_FOLDER_PATH);

        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();
            $pageTransfer = $this->createPageTransfer($data);
            $blockTransfer = $this->createBlockTransfer($data);

            $this->getFacade()->savePageBlockAndTouch($pageTransfer, $blockTransfer);
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
        $idBlock = $request->get(CmsBlockTable::REQUEST_ID_BLOCK);

        $form = $this->getDependencyContainer()
            ->createCmsBlockForm('update', $idBlock)
        ;

        $isSynced = $this->getFacade()->syncTemplate(self::CMS_FOLDER_PATH);

        $form->handleRequest();
        if ($form->isValid()) {
            $data = $form->getData();

            $pageTransfer = $this->createPageTransfer($data);
            $pageTransfer->setIdCmsPage($data[CmsBlockForm::FK_PAGE]);

            $this->updatePageAndBlock($data, $pageTransfer);

            $redirectUrl = self::REDIRECT_ADDRESS . '?' . CmsPageTable::REQUEST_ID_PAGE . '=' . $pageTransfer->getIdCmsPage();

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'isSynced' => $isSynced,
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
     * @param PageTransfer $pageTransfer
     */
    protected function updatePageAndBlock(array $data, PageTransfer $pageTransfer)
    {
        if (intval($data[CmsPageForm::CURRENT_TEMPLATE]) !== intval($data[CmsPageForm::FK_TEMPLATE])) {
            $this->getFacade()->deleteGlossaryKeysByIdPage($data[CmsBlockForm::FK_PAGE]);
        }
        $blockTransfer = $this->createBlockTransfer($data);

        $this->getFacade()->savePageBlockAndTouch($pageTransfer, $blockTransfer);
    }

    /**
     * @param array $data
     *
     * @return CmsBlockTransfer
     */
    private function createBlockTransfer(array $data)
    {
        $blockTransfer = new CmsBlockTransfer();
        $blockTransfer->fromArray($data, true);
        if ($data[CmsBlockForm::TYPE] === 'static') {
            $blockTransfer->setValue(0);
        }

        return $blockTransfer;
    }

    /**
     * @return LocaleFacade
     */
    private function getLocaleFacade()
    {
        return $this->getDependencyContainer()->getLocaleFacade();
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function searchCategoryAction(Request $request)
    {
        $term = $request->get('term');

        $searchedItems = $this->getQueryContainer()
            ->queryNodeByCategoryName($term, $this->getCurrentLocaleId())
            ->find()
        ;

        $result = [];
        foreach ($searchedItems as $category) {
            $result[] = [
                'id' => $category->getCategoryNodeId(),
                'name' => $category->getCategoryName(),
                'url' => $category->getUrl(),
            ];
        }

        return $this->jsonResponse($result);
    }

    /**
     * @return int
     */
    protected function getCurrentLocaleId()
    {
        return $this->getLocaleFacade()->getCurrentLocale()->getIdLocale();
    }

}
