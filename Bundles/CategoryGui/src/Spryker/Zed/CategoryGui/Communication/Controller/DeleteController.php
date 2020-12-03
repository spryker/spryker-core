<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Controller;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
 */
class DeleteController extends AbstractController
{
    protected const REQUEST_PARAM_ID_CATEGORY = 'id-category';

    /**
     * @uses \Spryker\Zed\CategoryGui\Communication\Controller\ListController::indexAction()
     */
    protected const ROUTE_CATEGORY_LIST = '/category-gui/list';

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer|null
     */
    protected $currentLocale;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCategory = $this->castId($request->get(static::REQUEST_PARAM_ID_CATEGORY));
        $categoryEntity = $this->getCategoryEntity($idCategory);

        $form = $this->getFactory()->createCategoryDeleteForm($idCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->getFactory()
                ->getCategoryFacade()
                ->delete($data['fk_category']);

            return $this->redirectResponse(static::ROUTE_CATEGORY_LIST);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'category' => $categoryEntity->toArray(),
            'subTrees' => $this->getSubTrees($categoryEntity),
            'urls' => $this->getUrls($categoryEntity),
            'relations' => $this->getRelations($categoryEntity),
        ]);
    }

    /**
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategory
     */
    protected function getCategoryEntity(int $idCategory): SpyCategory
    {
        $localeTransfer = $this->getCurrentLocale();

        return $this
            ->getFactory()
            ->getCategoryQueryContainer()
            ->queryCategory($localeTransfer->getIdLocale())
            ->filterByIdCategory($idCategory)
            ->findOne();
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     *
     * @return array
     */
    protected function getSubTrees(SpyCategory $categoryEntity): array
    {
        $subTrees = [];
        $categoryNodeCollection = $this
            ->getFactory()
            ->getCategoryFacade()
            ->getAllNodesByIdCategory($categoryEntity->getIdCategory());

        foreach ($categoryNodeCollection as $categoryNodeTransfer) {
            $subTree = $this->getSubTree($categoryNodeTransfer->getIdCategoryNode());

            if (count($subTree) === 0) {
                continue;
            }

            $parentCategoryEntity = $this->getParentCategoryEntity($categoryNodeTransfer);
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
    protected function getSubTree(int $idCategoryNode): array
    {
        $localeTransfer = $this->getCurrentLocale();

        return $this
            ->getFactory()
            ->getCategoryFacade()
            ->getSubTreeByIdCategoryNodeAndLocale($idCategoryNode, $localeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategory
     */
    protected function getParentCategoryEntity(NodeTransfer $categoryNodeTransfer): SpyCategory
    {
        $localeTransfer = $this->getCurrentLocale();

        return $this
            ->getFactory()
            ->getCategoryQueryContainer()
            ->queryCategory($localeTransfer->getIdLocale())
            ->useNodeQuery()
                ->filterByIdCategoryNode($categoryNodeTransfer->getFkParentCategoryNode())
            ->endUse()
            ->findOne();
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     *
     * @return array
     */
    protected function getUrls(SpyCategory $categoryEntity): array
    {
        $urls = [];
        $categoryNodeCollection = $this
            ->getFactory()
            ->getCategoryFacade()
            ->getAllNodesByIdCategory($categoryEntity->getIdCategory());

        foreach ($categoryNodeCollection as $categoryNodeEntity) {
            $urlCollection = $this
                ->getFactory()
                ->getCategoryQueryContainer()
                ->queryUrlByIdCategoryNode($categoryNodeEntity->getIdCategoryNode())
                ->find();

            foreach ($urlCollection as $urlEntity) {
                $urls[] = $urlEntity->toArray();
            }
        }

        return $urls;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     *
     * @return array
     */
    protected function getRelations(SpyCategory $categoryEntity): array
    {
        $relations = [];
        $localeTransfer = $this->getCurrentLocale();
        $categoryTransfer = $this->getCategoryTransferFromEntity($categoryEntity);
        $categoryRelationReadPlugins = $this->getFactory()->getCategoryRelationReadPlugins();

        foreach ($categoryRelationReadPlugins as $categoryRelationReadPlugin) {
            $relations[] = [
                'name' => $categoryRelationReadPlugin->getRelationName(),
                'list' => $categoryRelationReadPlugin->getRelations($categoryTransfer, $localeTransfer),
            ];
        }

        return $relations;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function getCategoryTransferFromEntity(SpyCategory $categoryEntity): CategoryTransfer
    {
        $categoryNodeCollection = $categoryEntity->getNodes();
        $categoryTransfer = (new CategoryTransfer())
            ->fromArray($categoryEntity->toArray(), true);

        foreach ($categoryNodeCollection as $categoryNodeEntity) {
            $categoryNodeTransfer = (new NodeTransfer())->fromArray($categoryNodeEntity->toArray());

            if ($categoryNodeEntity->getIsMain()) {
                $categoryTransfer->setCategoryNode($categoryNodeTransfer);
            } else {
                $categoryTransfer->addExtraParent($categoryNodeTransfer);
            }
        }

        return $categoryTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocale(): LocaleTransfer
    {
        if (!$this->currentLocale) {
            $this->currentLocale = $this->getFactory()->getLocaleFacade()->getCurrentLocale();
        }

        return $this->currentLocale;
    }
}
