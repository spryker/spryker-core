<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\Controller;

use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductCategoryFilterGui\Communication\ProductCategoryFilterGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductCategoryFilterGui\Persistence\ProductCategoryFilterGuiQueryContainerInterface getQueryContainer()
 */
class CategoryTreeController extends AbstractController
{
    public const PARAM_ID_ROOT_NODE = 'id-root-node';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idRootNode = $this->castId($request->query->get(self::PARAM_ID_ROOT_NODE));
        $localeTransfer = $this->getCurrentLocale();

        $mainCategory = $this->getQueryContainer()
            ->queryCategoryByIdAndLocale($idRootNode, $localeTransfer->getIdLocale())
            ->findOne();

        $categoryTree = $this
            ->getFactory()
            ->getCategoryFacade()
            ->getTreeNodeChildrenByIdCategoryAndLocale($idRootNode, $localeTransfer);

        $categoriesWithSpecificFilters = $this
            ->getFactory()
            ->getProductCategoryFilterFacade()
            ->getAllProductCategoriesWithFilters();

        return $this->viewResponse([
            'mainCategory' => $mainCategory,
            'categoryTree' => $categoryTree, //@deprecated Use parameter `childNodes` instead.
            'categoriesWithSpecificFilters' => $categoriesWithSpecificFilters,
            'nodeCollection' => $this->findCategoryNodeTree($idRootNode),
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocale()
    {
        return $this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale();
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeCollectionTransfer|null
     */
    protected function findCategoryNodeTree(int $idCategory): ?NodeCollectionTransfer
    {
        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategory($idCategory)
            ->setLocaleName($this->getCurrentLocale()->getLocaleName())
            ->setWithChildrenRecursively(true);

        $categoryTransfer = $this->getFactory()->getCategoryFacade()->findCategory($categoryCriteriaTransfer);

        if (!$categoryTransfer) {
            return null;
        }

        return $this->getCategoryChildNodeCollection($categoryTransfer);
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
