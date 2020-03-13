<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TableFilterTransfer;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToCategoryFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\ProductOfferGuiPageConfig;

class CategoryProductTableFilterDataProvider implements ProductTableFilterDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductOfferGuiPageToCategoryFacadeInterface $categoryFacade,
        ProductOfferGuiPageToLocaleFacadeInterface $localeFacade
    ) {
        $this->categoryFacade = $categoryFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\TableFilterTransfer
     */
    public function getFilterData(): TableFilterTransfer
    {
        $categoryCollectionTransfer = $this->categoryFacade->getAllCategoryCollection(
            $this->getCurrentLocaleTransfer()
        );

        $indexedCategoryNames = $this->getCategoryNamesIndexedByCategoryIds($categoryCollectionTransfer);

        return (new TableFilterTransfer())
            ->setKey(ProductOfferGuiPageConfig::PRODUCT_TABLE_CATEGORIES_FILTER_NAME)
            ->setTitle('Categories')
            ->setType('select')
            ->addOption(static::OPTION_NAME_MULTISELECT, false)
            ->addOption(static::OPTION_NAME_VALUES, $indexedCategoryNames);
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocaleTransfer(): LocaleTransfer
    {
        return $this->localeFacade->getCurrentLocale();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryCollectionTransfer $categoryCollectionTransfer
     *
     * @return array
     */
    protected function getCategoryNamesIndexedByCategoryIds(CategoryCollectionTransfer $categoryCollectionTransfer): array
    {
        $categoryFilterOptions = [];

        foreach ($categoryCollectionTransfer->getCategories() as $categoryTransfer) {
            $categoryFilterOptions[] = [
                static::OPTION_VALUE_KEY_TITLE => $categoryTransfer->getName(),
                static::OPTION_VALUE_KEY_VALUE => $categoryTransfer->getIdCategory(),
            ];
        }

        return $categoryFilterOptions;
    }
}
