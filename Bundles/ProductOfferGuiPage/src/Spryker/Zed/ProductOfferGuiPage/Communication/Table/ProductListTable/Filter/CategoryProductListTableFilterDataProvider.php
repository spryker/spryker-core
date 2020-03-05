<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\Filter;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TableFilterTransfer;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToCategoryFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToLocaleFacadeInterface;

class CategoryProductListTableFilterDataProvider implements ProductListTableFilterDataProviderInterface
{
    public const FILTER_NAME = 'category';

    protected const KEY_KEY = 'key';
    protected const KEY_VALUE = 'value';

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
            ->setKey(static::FILTER_NAME)
            ->setTitle('Category')
            ->setType('select')
            ->setIsMultiselect(false)
            ->setOptions($indexedCategoryNames);
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
        $indexedCategoryNames = [];

        foreach ($categoryCollectionTransfer->getCategories() as $categoryTransfer) {
            $indexedCategoryNames[] = [
                static::KEY_KEY => $categoryTransfer->getIdCategory(),
                static::KEY_VALUE => $categoryTransfer->getName(),
            ];
        }

        return $indexedCategoryNames;
    }
}
