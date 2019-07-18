<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\Controller;

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
            'categoryTree' => $categoryTree,
            'categoriesWithSpecificFilters' => $categoriesWithSpecificFilters,
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
}
