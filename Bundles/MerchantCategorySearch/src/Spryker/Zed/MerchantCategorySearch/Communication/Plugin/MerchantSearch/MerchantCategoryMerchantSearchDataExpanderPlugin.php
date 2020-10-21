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
 */
class MerchantCategoryMerchantSearchDataExpanderPlugin extends AbstractPlugin implements MerchantSearchDataExpanderPluginInterface
{
    protected const ID_MERCHANT = 'idMerchant';
    protected const MERCHANT_CATEGORY_IDS = 'merchantCategoryIds';

    /**
     * {@inheritDoc}
     * - Expands merchant search data with merchant category ids.
     *
     * @api
     *
     * @param array $merchantSearchData
     *
     * @return array
     */
    public function expand(array $merchantSearchData): array
    {
        $merchantCategoryTransfer = $this->getFactory()
            ->getMerchantCategoryFacade()
            ->get(
                (new MerchantCategoryCriteriaTransfer())
                    ->setIdMerchant($merchantSearchData[static::ID_MERCHANT])
            );

        $merchantCategoryIds = [];

        foreach ($merchantCategoryTransfer->getCategories() as $categoryTransfer) {
            $merchantCategoryIds[] = $categoryTransfer->getIdCategory();
        }

        $merchantSearchData[static::MERCHANT_CATEGORY_IDS] = $merchantCategoryIds;

        return $merchantSearchData;
    }
}
