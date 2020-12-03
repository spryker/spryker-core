<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Controller;

use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Shared\Category\CategoryConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Category\Communication\CategoryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Category\Business\CategoryFacadeInterface getFacade()
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface getRepository()
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
        $categoryTransfer = $this->findCategory($idCategory);
        if (!$categoryTransfer) {
            return $this->redirectResponse($this->getFactory()->getConfig()->getDefaultRedirectUrl());
        }

        $form = $this->getFactory()->createCategoryDeleteForm($idCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this
                ->getFacade()
                ->delete($data['fk_category']);

            return $this->redirectResponse($this->getFactory()->getConfig()->getDefaultRedirectUrl());
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'category' => $categoryTransfer,
            'subTrees' => $this->getSubTrees($categoryTransfer), //@deprecated Use property `childNodes` instead.
            'urls' => $this->getUrls($categoryTransfer),
            'relations' => $this->getRelations($categoryTransfer),
            'parentCategory' => $this->getParentCategoryEntity($categoryTransfer->getCategoryNode())->toArray(),
            'childNodes' => $this->getCategoryChildNodeCollection($categoryTransfer),
        ]);
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    protected function findCategory(int $idCategory): ?CategoryTransfer
    {
        $localeTransfer = $this->getFactory()->getCurrentLocale();
        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategory($idCategory)
            ->setLocaleName($localeTransfer->getLocaleName())
            ->setWithChildrenRecursively(true);

        $categoryTransfer = $this
            ->getFacade()
            ->findCategory($categoryCriteriaTransfer);

        if (!$categoryTransfer) {
            return null;
        }

        return $categoryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return array
     */
    protected function getSubTrees(CategoryTransfer $categoryTransfer)
    {
        $categoryNodeCollection = $this
            ->getFacade()
            ->getAllNodesByIdCategory($categoryTransfer->getIdCategory());
        $subTrees = [];

        foreach ($categoryNodeCollection as $categoryNodeTransfer) {
            $subTree = $this->getSubTree($categoryNodeTransfer->getIdCategoryNode());

            if ($subTree === [] || $categoryNodeTransfer->getFkParentCategoryNode() === null) {
                continue;
            }

            $parentCategoryEntity = $this->getParentCategoryEntity($categoryNodeTransfer);
            $subTrees[] = [
                'text' => $categoryTransfer->getLocalizedAttributes()->offsetGet(0)->getName(),
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
        /** @var \Orm\Zed\Category\Persistence\SpyCategory $parentCategoryEntity */
        $parentCategoryEntity = $this
            ->getQueryContainer()
            ->queryCategory($localeTransfer->getIdLocale())
            ->useNodeQuery()
                ->filterByIdCategoryNode($categoryNodeTransfer->getFkParentCategoryNode())
            ->endUse()
            ->findOne();

        return $parentCategoryEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return array
     */
    protected function getUrls(CategoryTransfer $categoryTransfer)
    {
        $categoryNodeCollection = $this
            ->getFacade()
            ->getAllNodesByIdCategory($categoryTransfer->getIdCategory());

        $urls = [];
        foreach ($categoryNodeCollection as $categoryNodeEntity) {
            $urlCollection = $this
                ->getQueryContainer()
                ->queryUrlByIdCategoryNode($categoryNodeEntity->getIdCategoryNode())
                ->find();

            foreach ($urlCollection as $urlEntity) {
                $urls[] = $urlEntity->toArray();
            }
        }

        return $urls;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return array
     */
    protected function getRelations(CategoryTransfer $categoryTransfer)
    {
        $localeTransfer = $this->getFactory()->getCurrentLocale();
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
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\NodeCollectionTransfer
     */
    protected function getCategoryChildNodeCollection(CategoryTransfer $categoryTransfer): NodeCollectionTransfer
    {
        $categoryNodeCollectionTransfer = $categoryTransfer->getNodeCollection();
        if (!$categoryNodeCollectionTransfer || $categoryNodeCollectionTransfer->getNodes()->count() === 0) {
            return new NodeCollectionTransfer();
        }

        return $categoryNodeCollectionTransfer->getNodes()->offsetGet(0)->getChildrenNodes() ?? new NodeCollectionTransfer();
    }
}
