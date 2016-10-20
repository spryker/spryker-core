<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Controller;

use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Spryker\Shared\Category\CategoryConstants;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Category\Communication\CategoryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Category\Business\CategoryFacade getFacade()
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface getQueryContainer()
 */
class DeleteController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCategory = $this->castId($request->get(CategoryConstants::PARAM_ID_CATEGORY));
        $categoryEntity = $this->getCategoryEntity($idCategory);

        $form = $this->getFactory()->createCategoryDeleteForm($idCategory);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $this
                ->getFacade()
                ->delete($data['fk_category']);

            return $this->redirectResponse('/category/root');
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'category' => $categoryEntity->toArray(),
            'subTrees' => $this->getSubTrees($categoryEntity),
        ]);
    }

    /**
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategory
     */
    protected function getCategoryEntity($idCategory)
    {
        $localeTransfer = $this->getFactory()->getCurrentLocale();
        $categoryEntity = $this
            ->getQueryContainer()
            ->queryCategory($localeTransfer->getIdLocale())
            ->filterByIdCategory($idCategory)
            ->findOne();

        return $categoryEntity;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     *
     * @return array
     */
    protected function getSubTrees(SpyCategory $categoryEntity)
    {
        $categoryNodeCollection = $this
            ->getFacade()
            ->getAllNodesByIdCategory($categoryEntity->getIdCategory());
        $subTrees = [];

        foreach ($categoryNodeCollection as $categoryNodeTransfer) {
            $parentCategoryEntity = $this->getParentCategoryEntity($categoryNodeTransfer);
            $subTree = $this->getSubTree($categoryNodeTransfer->getIdCategoryNode());
            $subTrees[] = [
                'text' => $categoryEntity->getAttributes()->getFirst()->getName(),
                'isMain' => $categoryNodeTransfer->getIsMain(),
                'parentCategory' => $parentCategoryEntity->toArray(),
                'tree' => $subTree,
            ];
        }

        return $subTrees;
    }

    /**
     * @param int $idCategoryNode
     *
     * @return array
     */
    protected function getSubTree($idCategoryNode)
    {
        $localeTransfer = $this->getFactory()->getCurrentLocale();
        $tree = $this
            ->getFacade()
            ->getSubTreeByIdCategoryNodeAndLocale($idCategoryNode, $localeTransfer);

        return $tree;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategory
     */
    protected function getParentCategoryEntity(NodeTransfer $categoryNodeTransfer)
    {
        $localeTransfer = $this->getFactory()->getCurrentLocale();
        $parentCategoryEntity = $this
            ->getQueryContainer()
            ->queryCategory($localeTransfer->getIdLocale())
            ->useNodeQuery()
                ->filterByIdCategoryNode($categoryNodeTransfer->getFkParentCategoryNode())
            ->endUse()
            ->findOne();

        return $parentCategoryEntity;
    }

    protected function getUrls()
    {

    }

}
