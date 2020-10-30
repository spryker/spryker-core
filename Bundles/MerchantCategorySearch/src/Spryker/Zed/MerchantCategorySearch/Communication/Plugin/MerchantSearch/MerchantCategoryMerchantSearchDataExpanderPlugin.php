<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategorySearch\Communication\Plugin\MerchantSearch;

use Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantSearchExtension\Dependency\Plugin\MerchantSearchDataExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantCategorySearch\Communication\MerchantCategorySearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantCategorySearch\MerchantCategorySearchConfig getConfig()
 */
class MerchantCategoryMerchantSearchDataExpanderPlugin extends AbstractPlugin implements MerchantSearchDataExpanderPluginInterface
{
    protected const ID_MERCHANT = 'id_merchant';
    protected const CATEGORY_KEYS = 'category-keys';

    protected const SEARCH_RESULT_DATA = 'search-result-data';

    /**
     * {@inheritDoc}
     * - Expands merchant search data with merchant category ids.
     *
     * @api
     *
     * @param mixed[] $merchantSearchData
     *
     * @return mixed[]
     */
    public function expand(array $merchantSearchData): array
    {
        $merchantCategoryResponseTransfer = $this->getFactory()
            ->getMerchantCategoryFacade()
            ->get(
                (new MerchantCategoryCriteriaTransfer())
                    ->setIdMerchant($merchantSearchData[static::SEARCH_RESULT_DATA][static::ID_MERCHANT])
            );

        $categoryKeys = [];

        foreach ($merchantCategoryResponseTransfer->getCategories() as $categoryTransfer) {
            $categoryKeys[] = $categoryTransfer->getCategoryKey();
        }

        $merchantSearchData[static::CATEGORY_KEYS] = $categoryKeys;

        return $merchantSearchData;
    }
}
