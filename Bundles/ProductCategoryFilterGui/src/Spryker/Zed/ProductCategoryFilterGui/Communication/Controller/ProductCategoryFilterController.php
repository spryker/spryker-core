<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\Controller;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\FacetResultFormatterPlugin;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductCategoryFilterGui\Communication\Form\ProductCategoryFilterForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductCategoryFilterGui\Communication\ProductCategoryFilterGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductCategoryFilterGui\Persistence\ProductCategoryFilterGuiQueryContainerInterface getQueryContainer()
 */
class ProductCategoryFilterController extends AbstractController
{
    /**
     * @var string
     */
    public const PARAM_ID_CATEGORY_NODE = 'id-category-node';

    /**
     * @var string
     */
    public const REDIRECT_ADDRESS = '/product-category-filter-gui/product-category-filter';

    /**
     * @var string
     */
    protected const SUCCESS_MESSAGE_CATEGORY_FILTERS_UPDATED = 'Filters for Category "%s" were updated successfully.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_INVALID_FILTERS = 'Filters for Category "%s" cannot be saved. Invalid filter(s) provided.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idCategory = $this->castId($request->query->get(static::PARAM_ID_CATEGORY_NODE));
        $localeTransfer = $this->getCurrentLocale();

        $categoryTransfer = $this->getCategory($idCategory, $localeTransfer->getIdLocaleOrFail());

        $productCategoryFilterDataProvider = $this->getFactory()
            ->createProductCategoryFilterDataProvider();

        $productCategoryFilterForm = $this->getFactory()
            ->getProductCategoryFilterForm(
                $productCategoryFilterDataProvider->getData(),
                $productCategoryFilterDataProvider->getOptions(),
            )
            ->handleRequest($request);

        $savedProductCategoryFilters = $this->getFactory()
            ->getProductCategoryFilterFacade()
            ->findProductCategoryFilterByCategoryId($idCategory);

        $categorySearchFilters = $this->getFactory()
            ->getCatalogClient()
            ->catalogSearch('', [PageIndexMap::CATEGORY => $idCategory])[FacetResultFormatterPlugin::NAME];

        $productCategoryFilterTransfer = $this->handleProductCategoryFilterForm(
            $productCategoryFilterForm,
            $categoryTransfer,
            array_keys($categorySearchFilters),
            $savedProductCategoryFilters->getIdProductCategoryFilter(),
        );

        if (!$productCategoryFilterTransfer) {
            $productCategoryFilterTransfer = $this->getFactory()
                ->createProductCategoryFilterFormatter()
                ->generateTransferWithJsonFromTransfer($savedProductCategoryFilters);
        }

        $filters = [];

        if (count($productCategoryFilterTransfer->getFilters()) === 0) {
            $filters = $this->getFactory()
                ->getProductCategoryFilterClient()
                ->updateFacetsByCategory(
                    $categorySearchFilters,
                    $productCategoryFilterTransfer->getFilterDataArray(),
                );
        }

        /** @var array<\Generated\Shared\Transfer\ProductCategoryFilterItemTransfer>|null $productCategoryFilters */
        $productCategoryFilters = $productCategoryFilterTransfer->getFilters();
        $nonSearchFilters = $this->getNonSearchFilters(
            ($productCategoryFilters !== null) ? (array)$productCategoryFilters : [],
            $categorySearchFilters,
        );

        return $this->viewResponse([
            'productCategoryFilterForm' => $productCategoryFilterForm->createView(),
            'category' => $categoryTransfer,
            'filters' => $filters,
            'productCategoryFilters' => $productCategoryFilterTransfer,
            'allFilters' => $categorySearchFilters,
            'nonSearchAttributes' => $nonSearchFilters,
        ]);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductCategoryFilterItemTransfer> $filters
     * @param array<\Generated\Shared\Transfer\FacetSearchResultTransfer> $searchFilters
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
        $idCategory = $this->castId($request->query->get(static::PARAM_ID_CATEGORY_NODE));

        $localeTransfer = $this->getCurrentLocale();
        $category = $this->getCategory($idCategory, $localeTransfer->getIdLocaleOrFail());

        $this->getFactory()
            ->getProductCategoryFilterFacade()
            ->deleteProductCategoryFilterByCategoryId($idCategory);

        $redirectUrl = static::REDIRECT_ADDRESS . '?' . static::PARAM_ID_CATEGORY_NODE . '=' . $idCategory;

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
        $category = new CategoryTransfer();
        $mainCategory = $this->getQueryContainer()
            ->queryCategoryByIdAndLocale($idCategory, $idLocale)
            ->findOne();

        if ($mainCategory === null) {
            return $category;
        }

        $category->setIdCategory($mainCategory->getFkCategory());
        $category->setName($mainCategory->getName());

        return $category;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $productCategoryFilterForm
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param array<int, int|string> $categorySearchFilters
     * @param int|null $idProductCategoryFilter
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer|null
     */
    protected function handleProductCategoryFilterForm(
        FormInterface $productCategoryFilterForm,
        CategoryTransfer $categoryTransfer,
        array $categorySearchFilters,
        ?int $idProductCategoryFilter = null
    ): ?ProductCategoryFilterTransfer {
        if (!$this->isProductCategoryFilterFormHandable($productCategoryFilterForm, $categoryTransfer)) {
            return null;
        }

        $productCategoryFilterTransfer = $this->getFactory()
            ->createProductCategoryFilterFormatter()
            ->generateTransferFromJson(
                $idProductCategoryFilter,
                $categoryTransfer->getIdCategoryOrFail(),
                $productCategoryFilterForm->getData()[ProductCategoryFilterForm::FIELD_FILTERS],
            );

        $isProductCategoryFilterValid = $this->getFactory()
            ->createProductCategoryFilterValidator()
            ->validate($productCategoryFilterTransfer, $categorySearchFilters);

        if (!$isProductCategoryFilterValid) {
            $this->addErrorMessage(static::ERROR_MESSAGE_INVALID_FILTERS, ['%s' => $categoryTransfer->getName()]);

            return null;
        }

        $productCategoryFilterTransfer = $this->getFactory()
            ->createProductCategoryFilterSaver()
            ->save($productCategoryFilterTransfer);

        $this->addSuccessMessage(static::SUCCESS_MESSAGE_CATEGORY_FILTERS_UPDATED, ['%s' => $categoryTransfer->getName()]);

        return $productCategoryFilterTransfer;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $productCategoryFilterForm
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return bool
     */
    protected function isProductCategoryFilterFormHandable(
        FormInterface $productCategoryFilterForm,
        CategoryTransfer $categoryTransfer
    ): bool {
        return $productCategoryFilterForm->isSubmitted()
            && $productCategoryFilterForm->isValid()
            && $categoryTransfer->getIdCategory();
    }
}
