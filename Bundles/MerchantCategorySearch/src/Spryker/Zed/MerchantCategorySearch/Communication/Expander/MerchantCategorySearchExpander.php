<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategorySearch\Communication\Expander;

use Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer;
use Generated\Shared\Transfer\MerchantSearchCollectionTransfer;
use Spryker\Zed\MerchantCategorySearch\Dependency\Facade\MerchantCategorySearchToMerchantCategoryFacadeInterface;

class MerchantCategorySearchExpander implements MerchantCategorySearchExpanderInterface
{
    protected const CATEGORY_KEYS = 'category-keys';

    /**
     * @var \Spryker\Zed\MerchantCategorySearch\Dependency\Facade\MerchantCategorySearchToMerchantCategoryFacadeInterface
     */
    protected $merchantCategoryFacade;

    /**
     * @param \Spryker\Zed\MerchantCategorySearch\Dependency\Facade\MerchantCategorySearchToMerchantCategoryFacadeInterface $merchantCategoryFacade
     */
    public function __construct(MerchantCategorySearchToMerchantCategoryFacadeInterface $merchantCategoryFacade)
    {
        $this->merchantCategoryFacade = $merchantCategoryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSearchCollectionTransfer
     */
    public function expand(MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer): MerchantSearchCollectionTransfer
    {
        $merchantCategoryResponseTransfer = $this->merchantCategoryFacade->get(
            (new MerchantCategoryCriteriaTransfer())
                ->setMerchantIds($this->extractMerchantIds($merchantSearchCollectionTransfer))
        );

        $categoryKeysIndexedByIdMerchant = [];
        foreach ($merchantCategoryResponseTransfer->getMerchantCategories() as $merchantCategoryTransfer) {
            /**
             * @var \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
             */
            $categoryTransfer = $merchantCategoryTransfer->getCategory();
            $categoryKeysIndexedByIdMerchant[$merchantCategoryTransfer][] = $categoryTransfer->getCategoryKey();
        }

        foreach ($merchantSearchCollectionTransfer->getMerchants() as $merchantSearchTransfer) {
            $merchantSearchData = $merchantSearchTransfer->getData();
            $merchantSearchData[static::CATEGORY_KEYS] = $categoryKeysIndexedByIdMerchant[$merchantSearchTransfer->getIdMerchant()] ?? [];

            $merchantSearchTransfer->setData($merchantSearchData);
        }

        return $merchantSearchCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer
     *
     * @return int[]
     */
    protected function extractMerchantIds(MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer): array
    {
        $merchantIds = [];

        foreach ($merchantSearchCollectionTransfer->getMerchants() as $merchantSearchTransfer) {
            /**
             * @var int $idMerchant
             */
            $idMerchant = $merchantSearchTransfer->getIdMerchant();

            $merchantIds[] = $idMerchant;
        }

        return $merchantIds;
    }
}
