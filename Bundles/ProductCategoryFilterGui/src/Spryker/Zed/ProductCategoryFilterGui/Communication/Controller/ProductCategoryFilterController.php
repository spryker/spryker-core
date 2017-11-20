<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\Controller;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\FacetResultFormatterPlugin;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductCategoryFilterGui\Communication\ProductCategoryFilterGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductCategoryFilterGui\Persistence\ProductCategoryFilterGuiQueryContainerInterface getQueryContainer()
 */
class ProductCategoryFilterController extends AbstractController
{
    const PARAM_ID_CATEGORY_NODE = 'id-category-node';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idCategory = $this->castId($request->query->get(self::PARAM_ID_CATEGORY_NODE));
        $localeTransfer = $this->getCurrentLocale();

        $category = $this->getCategory($idCategory, $localeTransfer->getIdLocale());

        $productCategoryFilterDataProvider = $this->getFactory()
            ->createProductCategoryFilterDataProvider();

        $productCategoryFilterForm = $this->getFactory()
            ->createProductCategoryFilterForm(
                $productCategoryFilterDataProvider->getData(),
                $productCategoryFilterDataProvider->getOptions()
            )
            ->handleRequest($request);

        $searchResultsForCategory = $this->getFactory()
            ->getCatalogClient()
            ->catalogSearch('', [PageIndexMap::CATEGORY => $idCategory]);

        $productCategoryFilters = $this->getFactory()
            ->getProductCategoryFilterFacade()
            ->findProductCategoryFilterByCategoryId($idCategory)
            ->getFilterDataArray();

        $filters = $this->getFactory()
            ->getProductCategoyFilterClient()
            ->updateFacetsByCategory($searchResultsForCategory[FacetResultFormatterPlugin::NAME], $idCategory, $localeTransfer->getLocaleName());

//        if ($productCategoryFilterForm->isValid()) {
            /** @var \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer */
//            $productCategoryFilterTransfer = $productCategoryFilterForm->getData();

//            $productCategoryFilterTransfer->setFkNavigation($idNavigation);
//            if ($idNavigationNode) {
//                $productCategoryFilterTransfer->setFkParentNavigationNode($idNavigationNode);
//            }
//
//            $productCategoryFilterTransfer = $this->getFactory()
//                ->getNavigationFacade()
//                ->createNavigationNode($productCategoryFilterTransfer);
//
//            $this->addSuccessMessage(sprintf(
//                'Navigation node "%s" was created successfully.',
//                $productCategoryFilterTransfer->getNavigationNodeLocalizedAttributes()->getArrayCopy()[0]->getTitle()
//            ));
//
//            $queryParams = [
//                static::PARAM_ID_NAVIGATION => $idNavigation,
//                static::PARAM_ID_NAVIGATION_NODE => $idNavigationNode,
//                static::PARAM_ID_SELECTED_TREE_NODE => $idNavigationNode,
//            ];
//
//            if ($idNavigationNode) {
//                return $this->redirectResponse(Url::generate('/navigation-gui/node/update', $queryParams)->build());
//            } else {
//                return $this->redirectResponse(Url::generate('/navigation-gui/node/create', $queryParams)->build());
//            }
//        }

        return $this->viewResponse([
            'productCategoryFilterForm' => $productCategoryFilterForm->createView(),
            'category' => $category,
            'filters' => $filters,
            'productCategoryFilters' => $productCategoryFilters,
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
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function getCategory($idCategory, $idLocale)
    {
        $mainCategory = $this->getQueryContainer()
            ->queryCategoryByIdAndLocale($idCategory, $idLocale)
            ->findOne();

        $category = new CategoryTransfer();
        $category->setIdCategory($mainCategory->getFkCategory());
        $category->setName($mainCategory->getName());

        return $category;
    }
}
