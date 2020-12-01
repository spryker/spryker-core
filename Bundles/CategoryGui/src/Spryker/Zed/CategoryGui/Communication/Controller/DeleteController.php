<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Controller;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Spryker\Shared\Category\CategoryConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
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

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->getFactory()
                ->getCategoryFacade()
                ->delete($data['fk_category']);

            return $this->redirectResponse($this->getFactory()->getConfig()->getDefaultRedirectUrl());
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
    protected function getCategoryEntity($idCategory)
    {
        $localeTransfer = $this->getFactory()->getCurrentLocale();
        $categoryEntity = $this
            ->getFactory()
            ->getCategoryQueryContainer()
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
            ->getFactory()
            ->getCategoryFacade()
            ->getAllNodesByIdCategory($categoryEntity->getIdCategory());
        $subTrees = [];

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
    protected function getSubTree($idCategoryNode)
    {
        $localeTransfer = $this->getFactory()->getCurrentLocale();
        $tree = $this
            ->getFactory()
            ->getCategoryFacade()
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
        /** @var \Orm\Zed\Category\Persistence\SpyCategory $parentCategoryEntity */
        $parentCategoryEntity = $this
            ->getFactory()
            ->getCategoryQueryContainer()
            ->queryCategory($localeTransfer->getIdLocale())
            ->useNodeQuery()
                ->filterByIdCategoryNode($categoryNodeTransfer->getFkParentCategoryNode())
            ->endUse()
            ->findOne();

        return $parentCategoryEntity;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     *
     * @return array
     */
    protected function getUrls(SpyCategory $categoryEntity)
    {
        $categoryNodeCollection = $this
            ->getFactory()
            ->getCategoryFacade()
            ->getAllNodesByIdCategory($categoryEntity->getIdCategory());

        $urls = [];
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
    protected function getRelations(SpyCategory $categoryEntity)
    {
        $localeTransfer = $this->getFactory()->getCurrentLocale();
        $categoryTransfer = $this->getCategoryTransferFromEntity($categoryEntity);
        $relationPlugins = $this->getFactory()->getRelationReadPluginStack();
        $relations = [];

        foreach ($relationPlugins as $relationPlugin) {
            $relations[] = [
                'name' => $relationPlugin->getRelationName(),
                'list' => $relationPlugin->getRelations($categoryTransfer, $localeTransfer),
            ];
        }

        return $relations;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function getCategoryTransferFromEntity(SpyCategory $categoryEntity)
    {
        $categoryTransfer = (new CategoryTransfer())->fromArray($categoryEntity->toArray(), true);
        $categoryNodeCollection = $categoryEntity->getNodes();

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
}
