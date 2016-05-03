<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Controller;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\PageTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Cms\Communication\Form\CmsBlockForm;
use Spryker\Zed\Cms\Communication\Form\CmsPageForm;
use Spryker\Zed\Cms\Communication\Table\CmsBlockTable;
use Spryker\Zed\Cms\Communication\Table\CmsPageTable;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Cms\Communication\CmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\Cms\Business\CmsFacade getFacade()
 * @method \Spryker\Zed\Cms\Persistence\CmsQueryContainer getQueryContainer()
 */
class BlockController extends AbstractController
{

    const REDIRECT_ADDRESS = '/cms/glossary';
    const CMS_FOLDER_PATH = '@Cms/template/';

    /**
     * @return array
     */
    public function indexAction()
    {
        $blockTable = $this->getFactory()
            ->createCmsBlockTable($this->getCurrentIdLocale());

        return [
            'blocks' => $blockTable->render(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()
            ->createCmsBlockTable($this->getCurrentIdLocale());

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request)
    {
        $isSynced = $this->getFacade()->syncTemplate(self::CMS_FOLDER_PATH);

        $dataProvider = $this->getFactory()->createCmsBlockFormDataProvider();
        $form = $this
            ->getFactory()
            ->createCmsBlockForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $pageTransfer = $this->createPageTransfer($data);
            $blockTransfer = $this->createBlockTransfer($data);

            $this->getFacade()->savePageBlockAndTouch($pageTransfer, $blockTransfer);
            //FIXME: Use proper URL class
            $redirectUrl = self::REDIRECT_ADDRESS . '?' . CmsPageTable::REQUEST_ID_PAGE . '=' . $pageTransfer->getIdCmsPage();

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'isSynced' => $isSynced,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function editAction(Request $request)
    {
        $idBlock = $this->castId($request->query->get(CmsBlockTable::REQUEST_ID_BLOCK));
        $isSynced = $this->getFacade()->syncTemplate(self::CMS_FOLDER_PATH);

        $dataProvider = $this->getFactory()->createCmsBlockFormDataProvider();
        $form = $this->getFactory()
            ->createCmsBlockForm(
                $dataProvider->getData($idBlock),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $pageTransfer = $this->createPageTransfer($data);
            $pageTransfer->setIdCmsPage($data[CmsBlockForm::FIELD_FK_PAGE]);

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
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    protected function createPageTransfer(array $data)
    {
        $pageTransfer = new PageTransfer();
        $pageTransfer->fromArray($data, true);

        return $pageTransfer;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     *
     * @return void
     */
    protected function updatePageAndBlock(array $data, PageTransfer $pageTransfer)
    {
        if ((int)$data[CmsPageForm::FIELD_CURRENT_TEMPLATE] !== (int)$data[CmsPageForm::FIELD_FK_TEMPLATE]) {
            $this->getFacade()->deleteGlossaryKeysByIdPage($data[CmsBlockForm::FIELD_FK_PAGE]);
        }
        $blockTransfer = $this->createBlockTransfer($data);

        $this->getFacade()->savePageBlockAndTouch($pageTransfer, $blockTransfer);
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    protected function createBlockTransfer(array $data)
    {
        $blockTransfer = new CmsBlockTransfer();
        $blockTransfer->fromArray($data, true);
        if ($data[CmsBlockForm::FIELD_TYPE] === 'static') {
            $blockTransfer->setValue(0);
        }

        return $blockTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function searchCategoryAction(Request $request)
    {
        $term = $request->query->get('term');

        $searchedItems = $this->getQueryContainer()
            ->queryNodeByCategoryName($term, $this->getCurrentIdLocale())
            ->find();

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
    protected function getCurrentIdLocale()
    {
        $localeFacade = $this->getFactory()->getLocaleFacade();
        return $localeFacade->getCurrentLocale()->getIdLocale();
    }

}
