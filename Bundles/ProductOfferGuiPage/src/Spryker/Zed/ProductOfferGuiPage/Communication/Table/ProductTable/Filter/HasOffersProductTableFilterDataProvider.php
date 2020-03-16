<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter;

use Generated\Shared\Transfer\TableFilterTransfer;
use Spryker\Zed\ProductOfferGuiPage\ProductOfferGuiPageConfig;

class HasOffersProductTableFilterDataProvider implements ProductTableFilterDataProviderInterface
{
    protected const OPTION_NAME_WITH_OFFERS = 'With Offers';
    protected const OPTION_NAME_WITHOUT_OFFERS = 'Without Offers';

    /**
     * @return \Generated\Shared\Transfer\TableFilterTransfer
     */
    public function getFilterData(): TableFilterTransfer
    {
        return (new TableFilterTransfer())
            ->setKey(ProductOfferGuiPageConfig::PRODUCT_TABLE_HAS_OFFERS_FILTER_NAME)
            ->setTitle('Offers')
            ->setType('select')
            ->addOption(static::OPTION_NAME_MULTISELECT, false)
            ->addOption(static::OPTION_NAME_VALUES, $this->getIsActiveValues());
    }

    /**
     * @return int[][]
     */
    protected function getIsActiveValues(): array
    {
        return [
            [
                static::OPTION_VALUE_KEY_TITLE => static::OPTION_NAME_WITH_OFFERS,
                static::OPTION_VALUE_KEY_VALUE => 1,
                ],
            [
                static::OPTION_VALUE_KEY_TITLE => static::OPTION_NAME_WITHOUT_OFFERS,
                static::OPTION_VALUE_KEY_VALUE => 2,
                ],
        ];
    }
}
