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
use Spryker\Zed\ProductCategoryFilterGui\Communication\Form\ProductCategoryFilterForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductCategoryFilterGui\Communication\ProductCategoryFilterGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductCategoryFilterGui\Persistence\ProductCategoryFilterGuiQueryContainerInterface getQueryContainer()
 */
class ProductCategoryFilterController extends AbstractController
{
    const PARAM_ID_CATEGORY_NODE = 'id-category-node';
    const REDIRECT_ADDRESS = '/product-category-filter-gui/product-category-filter';

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

        $productCategoryFilterFormatter = $this->getFactory()->createProductCategoryFilterFormatter();

        $productCategoryFilterForm = $this->getFactory()
            ->createProductCategoryFilterForm(
                $productCategoryFilterDataProvider->getData(),
                $productCategoryFilterDataProvider->getOptions()
            )
            ->handleRequest($request);

        $savedProductCategoryFilters = $this->getFactory()
            ->getProductCategoryFilterFacade()
            ->findProductCategoryFilterByCategoryId($idCategory);

        $productCategoryFilterTransfer = $productCategoryFilterFormatter
            ->generateTransferWithJsonFromTransfer($savedProductCategoryFilters);

        $searchResultsForCategory = $this->getFactory()
            ->getCatalogClient()
            ->catalogSearch('', [PageIndexMap::CATEGORY => $idCategory]);

        if ($productCategoryFilterForm->isValid()) {
            $productCategoryFilterTransfer = $productCategoryFilterFormatter->generateTransferFromJson(
                $savedProductCategoryFilters->getIdProductCategoryFilter(),
                $idCategory,
                $productCategoryFilterForm->getData()[ProductCategoryFilterForm::FIELD_FILTERS]
            );

            $facadeFunction = 'createProductCategoryFilter';
            if ($productCategoryFilterTransfer->getIdProductCategoryFilter()) {
                $facadeFunction = 'updateProductCategoryFilter';
            }

            $this->getFactory()
                ->getProductCategoryFilterFacade()
                ->$facadeFunction($productCategoryFilterTransfer);

            $this->addSuccessMessage(sprintf('Filters for Category "%s" were updated successfully.', $category->getName()));
        }

        $filters = [];

        if (count($productCategoryFilterTransfer->getFilters()) === 0) {
            $filters = $this->getFactory()
                ->getProductCategoryFilterClient()
                ->updateFacetsByCategory(
                    $searchResultsForCategory[FacetResultFormatterPlugin::NAME],
                    $productCategoryFilterTransfer->getFilterDataArray()
                );
        }

        return $this->viewResponse([
            'productCategoryFilterForm' => $productCategoryFilterForm->createView(),
            'category' => $category,
            'filters' => $filters,
            'productCategoryFilters' => $productCategoryFilterTransfer,
            'allFilters' => $searchResultsForCategory[FacetResultFormatterPlugin::NAME],
            'nonSearchAttributes' => array_diff_key($productCategoryFilterTransfer->getFilterDataArray(), $searchResultsForCategory[FacetResultFormatterPlugin::NAME]),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function resetAction(Request $request)
    {
        $idCategory = $this->castId($request->query->get(self::PARAM_ID_CATEGORY_NODE));

        $localeTransfer = $this->getCurrentLocale();
        $category = $this->getCategory($idCategory, $localeTransfer->getIdLocale());

        $this->getFactory()
            ->getProductCategoryFilterFacade()
            ->deleteProductCategoryFilterByCategoryId($idCategory);

        $redirectUrl = self::REDIRECT_ADDRESS . '?' . self::PARAM_ID_CATEGORY_NODE . '=' . $idCategory;

        $this->addSuccessMessage(sprintf('Filters for Category "%s" were deleted successfully.', $category->getName()));

        return $this->redirectResponse($redirectUrl);
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
