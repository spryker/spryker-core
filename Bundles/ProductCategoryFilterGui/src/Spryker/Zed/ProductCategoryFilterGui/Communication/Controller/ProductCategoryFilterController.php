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
    public const PARAM_ID_CATEGORY_NODE = 'id-category-node';
    public const REDIRECT_ADDRESS = '/product-category-filter-gui/product-category-filter';

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
            ->getProductCategoryFilterForm(
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

        if ($productCategoryFilterForm->isSubmitted() && $productCategoryFilterForm->isValid()) {
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

            $this->addSuccessMessage('Filters for Category "%s" were updated successfully.', ['%s' => $category->getName()]);
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

        /** @var \Generated\Shared\Transfer\ProductCategoryFilterItemTransfer[]|null $productCategoryFilters */
        $productCategoryFilters = $productCategoryFilterTransfer->getFilters();
        $nonSearchFilters = $this->getNonSearchFilters(
            ($productCategoryFilters !== null) ? (array)$productCategoryFilters : [],
            $searchResultsForCategory[FacetResultFormatterPlugin::NAME]
        );

        return $this->viewResponse([
            'productCategoryFilterForm' => $productCategoryFilterForm->createView(),
            'category' => $category,
            'filters' => $filters,
            'productCategoryFilters' => $productCategoryFilterTransfer,
            'allFilters' => $searchResultsForCategory[FacetResultFormatterPlugin::NAME],
            'nonSearchAttributes' => $nonSearchFilters,
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryFilterItemTransfer[] $filters
     * @param \Generated\Shared\Transfer\FacetSearchResultTransfer[] $searchFilters
     *
     * @return array
     */
    protected function getNonSearchFilters(array $filters, array $searchFilters)
    {
        $nonSearchFilters = [];
        foreach ($filters as $filter) {
            if (!isset($searchFilters[$filter->getKey()])) {
                $nonSearchFilters[] = $filter;
            }
        }

        return $nonSearchFilters;
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

        $this->addSuccessMessage('Filters for Category "%s" were deleted successfully.', ['%s' => $category->getName()]);

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
